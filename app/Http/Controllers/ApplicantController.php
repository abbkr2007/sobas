<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $applicants = Application::select(['id', 'application_id', 'surname', 'firstname', 'middlename', 'application_type', 'status', 'created_at']);
                
                // Debug: Check if we have data
                Log::info('Applicants count: ' . $applicants->count());
                
                return DataTables::of($applicants)
                ->addColumn('full_name', function ($row) {
                    $names = [];
                    if ($row->firstname) $names[] = $row->firstname;
                    if ($row->middlename) $names[] = $row->middlename;
                    if ($row->surname) $names[] = $row->surname;
                    return count($names) > 0 ? implode(' ', $names) : 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->status ?? 'Pending';
                    $badgeClass = match($status) {
                        'Submitted' => 'bg-info',
                        'Admitted' => 'bg-success', 
                        'Not Admitted' => 'bg-danger',
                        default => 'bg-warning'
                    };
                    return '<span class="badge ' . $badgeClass . '">' . $status . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-sm btn-outline-primary view-applicant" data-id="'.$row->id.'">View</button> <a href="/applicant/'.$row->id.'/download-biodata" class="btn btn-sm btn-outline-success">Download</a>';
                })
                ->editColumn('application_type', function ($row) {
                    return $row->application_type ? strtoupper(str_replace('_', ' ', $row->application_type)) : '';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
            } catch (\Exception $e) {
                Log::error('DataTable error: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to load data'], 500);
            }
        }

        return view('applicants.index');
    }

    public function show($id)
    {
        try {
            $applicant = Application::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'applicant' => $applicant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found'
            ], 404);
        }
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

    public function details($id)
    {
        try {
            $applicant = Application::findOrFail($id);
            
            // Format O'Level results
            $oLevelResults = [];
            
            // First exam results
            if ($applicant->first_subjects && $applicant->first_grades) {
                // Check if data is already an array or needs to be exploded
                $subjects = is_array($applicant->first_subjects) 
                    ? $applicant->first_subjects 
                    : explode(',', $applicant->first_subjects);
                $grades = is_array($applicant->first_grades) 
                    ? $applicant->first_grades 
                    : explode(',', $applicant->first_grades);
                
                for ($i = 0; $i < count($subjects) && $i < count($grades); $i++) {
                    $oLevelResults[] = [
                        'subject' => trim($subjects[$i]),
                        'grade' => trim($grades[$i]),
                        'year' => $applicant->first_exam_year ?? 'N/A'
                    ];
                }
            }
            
            // Second exam results
            if ($applicant->second_subjects && $applicant->second_grades) {
                // Check if data is already an array or needs to be exploded
                $subjects = is_array($applicant->second_subjects) 
                    ? $applicant->second_subjects 
                    : explode(',', $applicant->second_subjects);
                $grades = is_array($applicant->second_grades) 
                    ? $applicant->second_grades 
                    : explode(',', $applicant->second_grades);
                
                for ($i = 0; $i < count($subjects) && $i < count($grades); $i++) {
                    $oLevelResults[] = [
                        'subject' => trim($subjects[$i]),
                        'grade' => trim($grades[$i]),
                        'year' => $applicant->second_exam_year ?? 'N/A'
                    ];
                }
            }
            
            $fullName = collect([$applicant->firstname, $applicant->middlename, $applicant->surname])
                       ->filter()
                       ->implode(' ');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $applicant->id,
                    'application_id' => $applicant->application_id,
                    'full_name' => $fullName ?: 'N/A',
                    'email' => $applicant->email,
                    'phone' => $applicant->phone,
                    'application_type' => $applicant->application_type ? strtoupper(str_replace('_', ' ', $applicant->application_type)) : 'N/A',
                    'status' => 'Pending', // You can add a status field to the database if needed
                    'created_at' => $applicant->created_at ? $applicant->created_at->format('M d, Y - H:i') : 'N/A',
                    'o_level_results' => $oLevelResults
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found: ' . $e->getMessage()
            ], 404);
        }
    }

    public function downloadBiodata($id)
    {
        try {
            $applicant = Application::findOrFail($id);
            
            // Generate PDF or return biodata file
            // For now, we'll create a simple response
            $fullName = collect([$applicant->firstname, $applicant->middlename, $applicant->surname])
                       ->filter()
                       ->implode(' ');
            
            $biodataContent = "BIODATA\n\n";
            $biodataContent .= "Application ID: " . ($applicant->application_id ?? 'N/A') . "\n";
            $biodataContent .= "Full Name: " . ($fullName ?: 'N/A') . "\n";
            $biodataContent .= "Email: " . ($applicant->email ?? 'N/A') . "\n";
            $biodataContent .= "Phone: " . ($applicant->phone ?? 'N/A') . "\n";
            $biodataContent .= "Application Type: " . ($applicant->application_type ? strtoupper(str_replace('_', ' ', $applicant->application_type)) : 'N/A') . "\n";
            $biodataContent .= "Date Applied: " . ($applicant->created_at ? $applicant->created_at->format('M d, Y') : 'N/A') . "\n";
            
            $fileName = 'biodata_' . ($applicant->application_id ?? $applicant->id) . '.txt';
            
            return response($biodataContent)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found: ' . $e->getMessage()
            ], 404);
        }
    }

    public function updateField(Request $request, $id)
    {
        try {
            $applicant = Application::findOrFail($id);
            
            // Handle both form data and JSON requests
            $field = $request->input('field') ?? $request->json('field');
            $value = $request->input('value') ?? $request->json('value');
            
            // Log the request for debugging
            Log::info('Update field request:', [
                'id' => $id,
                'field' => $field,
                'value' => $value,
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'all_data' => $request->all()
            ]);
            
            // Validate allowed fields
            $allowedFields = ['application_type', 'status'];
            if (!in_array($field, $allowedFields)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid field: ' . $field
                ], 400);
            }
            
            // Validate required data
            if (empty($field) || $value === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Field and value are required'
                ], 400);
            }
            
            // Update the field
            $applicant->$field = $value;
            $applicant->save();
            
            Log::info('Field updated successfully:', [
                'id' => $id,
                'field' => $field,
                'old_value' => $applicant->getOriginal($field),
                'new_value' => $value
            ]);
            
            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $field)) . ' updated successfully',
                'data' => [
                    'field' => $field,
                    'value' => $value
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating field:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating field: ' . $e->getMessage()
            ], 500);
        }
    }

    public function admissionLetter($id)
    {
        try {
            $applicant = Application::findOrFail($id);
            
            $fullName = collect([$applicant->firstname, $applicant->middlename, $applicant->surname])
                       ->filter()
                       ->implode(' ');
            
            // Create admission letter content
            $letterContent = $this->generateAdmissionLetterPDF($applicant, $fullName);
            
            return $letterContent;
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating admission letter: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateAdmissionLetterPDF($applicant, $fullName)
    {
        // For now, create a simple HTML page that can be printed as PDF
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Admission Letter</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 40px; }
        .letterhead { color: #28a745; font-size: 24px; font-weight: bold; }
        .sub-header { color: #6c757d; font-size: 16px; }
        .content { margin: 30px 0; }
        .recipient { margin: 20px 0; }
        .signature { margin-top: 60px; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #6c757d; }
        @media print {
            body { margin: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="letterhead">USMANU DANFODIYO UNIVERSITY SOKOTO</div>
        <div class="sub-header">Office of Admissions</div>
        <div class="sub-header">PMB 2346, Sokoto, Nigeria</div>
    </div>

    <div class="content">
        <p><strong>Date:</strong> ' . date('F j, Y') . '</p>
        
        <div class="recipient">
            <strong>' . ($fullName ?: 'Dear Applicant') . '</strong><br>
            Application ID: ' . ($applicant->application_id ?? $applicant->id) . '
        </div>

        <h3>ADMISSION LETTER</h3>

        <p>Dear ' . ($fullName ?: 'Applicant') . ',</p>

        <p>We are pleased to inform you that you have been <strong>ADMITTED</strong> into the ' . 
        ($applicant->application_type ? strtoupper(str_replace('_', ' ', $applicant->application_type)) : 'PROGRAM') . 
        ' for the current academic session.</p>

        <h4>Admission Details:</h4>
        <ul>
            <li><strong>Full Name:</strong> ' . ($fullName ?: 'N/A') . '</li>
            <li><strong>Application ID:</strong> ' . ($applicant->application_id ?? $applicant->id) . '</li>
            <li><strong>Program:</strong> ' . ($applicant->application_type ? strtoupper(str_replace('_', ' ', $applicant->application_type)) : 'N/A') . '</li>
            <li><strong>Admission Status:</strong> ' . ucfirst($applicant->status ?? 'Pending') . '</li>
            <li><strong>Date of Application:</strong> ' . ($applicant->created_at ? $applicant->created_at->format('F j, Y') : 'N/A') . '</li>
        </ul>

        <h4>Next Steps:</h4>
        <ol>
            <li>Report to the admissions office within 30 days of receiving this letter</li>
            <li>Complete your registration process</li>
            <li>Pay all required fees</li>
            <li>Attend the orientation program</li>
        </ol>

        <p>Congratulations on your admission! We look forward to welcoming you to our academic community.</p>

        <div class="signature">
            <p>Sincerely,</p>
            <br><br>
            <p><strong>Registrar</strong><br>
            Usmanu Danfodiyo University Sokoto</p>
        </div>
    </div>

    <div class="footer">
        <p>This is an official document from Usmanu Danfodiyo University Sokoto</p>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
            Print This Letter
        </button>
    </div>
</body>
</html>';

        return response($html)->header('Content-Type', 'text/html');
    }
}