<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Response;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $applicants = Application::select(['id', 'application_id', 'surname', 'firstname', 'middlename', 'application_type']);

            return DataTables::of($applicants)
                ->addColumn('full_name', function ($row) {
                    $fullName = collect([$row->firstname, $row->middlename, $row->surname])
                        ->filter()
                        ->implode(' ');
                    return $fullName ?: 'N/A';
                })
                ->addColumn('application_type_formatted', function ($row) {
                    return ucfirst(str_replace('_', ' ', $row->application_type ?? 'N/A'));
                })
                ->make(true);
        }

        return view('applicants.index');
    }

    public function export()
    {
        try {
            // Get all applications with all fields
            $applications = Application::all();

            // Define the CSV headers
            $headers = [
                'id', 'application_id', 'surname', 'firstname', 'middlename', 'phone', 'email', 'dob', 
                'place_of_birth', 'gender', 'state', 'lga', 'town', 'country', 'foreign_country', 
                'home_address', 'guardian', 'guardian_address', 'guardian_phone', 'application_type', 
                'photo', 'schools', 'first_exam_type', 'first_exam_year', 'first_exam_number', 
                'first_center_number', 'first_subjects', 'first_grades', 'second_exam_type', 
                'second_exam_year', 'second_exam_number', 'second_center_number', 'second_subjects', 
                'second_grades', 'created_at', 'updated_at'
            ];

            // Generate filename with current date
            $filename = 'applicants_export_' . date('Y-m-d_H-i-s') . '.csv';

            // Set headers for CSV download
            $headers_http = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            // Create a callback function for the response
            $callback = function() use ($applications, $headers) {
                $file = fopen('php://output', 'w');
                
                // Add headers to CSV
                fputcsv($file, $headers);

                // Check if there are applications
                if ($applications->count() > 0) {
                    // Add data rows
                    foreach ($applications as $application) {
                        $row = [];
                        foreach ($headers as $header) {
                            $value = $application->$header ?? '';
                            
                            // Handle JSON fields
                            if (in_array($header, ['schools', 'first_subjects', 'first_grades', 'second_subjects', 'second_grades'])) {
                                if (is_array($value)) {
                                    $value = json_encode($value);
                                } elseif (is_string($value) && !empty($value)) {
                                    // Keep as is if already a string
                                    $value = $value;
                                } else {
                                    $value = '';
                                }
                            }
                            
                            $row[] = $value;
                        }
                        fputcsv($file, $row);
                    }
                } else {
                    // Add a message row if no data
                    $emptyRow = array_fill(0, count($headers), '');
                    $emptyRow[0] = 'No applicant data available';
                    fputcsv($file, $emptyRow);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers_http);

        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
}