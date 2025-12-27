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
                    if ($row->firstname) $names[] = ucfirst(strtolower($row->firstname));
                    if ($row->middlename) $names[] = ucfirst(strtolower($row->middlename));
                    if ($row->surname) $names[] = ucfirst(strtolower($row->surname));
                    return count($names) > 0 ? implode(' ', $names) : 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->status ?? 'Pending';
                    $formattedStatus = ucfirst(strtolower($status));
                    $badgeClass = match($status) {
                        'Admitted' => 'bg-success', 
                        'Pending' => 'bg-warning',
                        default => 'bg-warning'
                    };
                    return '<span class="badge ' . $badgeClass . '">' . $formattedStatus . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $actions = '<button class="btn btn-sm btn-outline-primary view-applicant" data-id="'.$row->id.'" title="View Details"><i class="fas fa-eye" style="font-size: 12px;"></i></button>';
                    
                    // Only show download button if status is exactly "Admitted" (case-sensitive)
                    if ($row->status !== null && trim($row->status) === 'Admitted') {
                        $actions .= ' <a href="' . route('applicant.download-admission-letter', $row->id) . '" 
                           class="btn btn-success btn-sm ms-1" 
                           title="Download Admission Letter">
                           <i class="fas fa-download" style="font-size: 12px;"></i>
                       </a>';
                    }
                    
                    return $actions;
                })
                ->editColumn('application_type', function ($row) {
                    return $row->application_type ? ucwords(str_replace('_', ' ', strtolower($row->application_type))) : '';
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
                    'application_type' => $applicant->application_type ?? 'N/A',
                    'status' => $applicant->status ?? 'Pending',
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
            $oldStatus = $applicant->status;
            $applicant->$field = $value;
            $applicant->save();

            Log::info('Field updated successfully:', [
                'id' => $id,
                'field' => $field,
                'old_value' => $applicant->getOriginal($field),
                'new_value' => $value
            ]);

            // Always send admitted email when status is set to Admitted
            if ($field === 'status' && $value === 'Admitted') {
                try {
                    \Mail::to($applicant->email)->send(new \App\Mail\ApplicantAdmittedMail($applicant));
                } catch (\Exception $e) {
                    \Log::error('Failed to send admitted email: ' . $e->getMessage());
                }
            }

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
        // Check if user is authenticated and is admin
        if (!auth()->check() || auth()->user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }
        
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
        // Get dynamic data from applicant
        $currentDate = now()->format('d-m-Y');
        $studentName = strtoupper($fullName);
        $programme = strtoupper($applicant->application_type ?? 'N/A');
        $applicationId = $applicant->application_id ?? 'N/A';
        
        // Get logo URL
        $logoUrl = 'https://sobas.cloud/images/logo.png';
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Confirmation Letter</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            background: #f8f9fa;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #2c3e50;
            background: #f8f9fa;
            padding: 0;
        }

        .a4-sheet {
            width: 210mm;
            height: 297mm;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            border-radius: 8px;
            border: 1.5px solid #2c5aa0;
            padding: 11mm 18mm;
            position: relative;
            box-sizing: border-box;
            overflow: hidden;
        }

        @media print {
            body, html {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .a4-sheet {
                width: 210mm;
                height: 297mm;
                margin: 0 !important;
                padding: 11mm 18mm !important;
                box-shadow: none;
                border: none;
                border-radius: 0;
                page-break-after: avoid;
                page-break-inside: avoid;
                overflow: hidden;
            }
            .print-btn { 
                display: none !important; 
                visibility: hidden !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 7px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #2c5aa0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .header h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c5aa0;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        .header h2 {
            font-size: 1rem;
            font-weight: 400;
            color: #444;
            margin-bottom: 2px;
        }
        .header h3 {
            font-size: 0.95rem;
            font-weight: 400;
            color: #888;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 2.5rem;
            color: rgba(44, 90, 160, 0.04);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
            letter-spacing: 2px;
            user-select: none;
        }

        .content {
            position: relative;
            z-index: 2;
        }
        .document-title {
            text-align: center;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 7px 0;
            margin: 0 -20mm 12px -20mm;
            font-size: 1.05rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-radius: 6px 6px 0 0;
        }
        .date-section {
            text-align: right;
            margin-bottom: 7px;
            color: #7f8c8d;
            font-style: italic;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 2px;
            font-size: 0.9rem;
        }
        .student-info {
            background: #f8f9fa;
            border-left: 5px solid #2c5aa0;
            padding: 6px 12px;
            margin: 10px 0 12px 0;
            border-radius: 0 8px 8px 0;
        }
        .student-name {
            font-size: 1.05rem;
            font-weight: bold;
            color: #2c5aa0;
        }
        .admission-details {
            background: #f9f9f9;
            border: 1.5px solid #fed7d7;
            padding: 6px 12px;
            margin: 10px 0;
            border-radius: 4px;
            position: relative;
        }
        .admission-details::before {
            content: '🎓';
            position: absolute;
            top: -12px;
            left: 12px;
            background: #fff;
            padding: 0 6px;
            font-size: 0.95rem;
        }
        .program-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 5px 10px;
            border-radius: 3px;
            margin: 6px 0;
            border-left: 5px solid #28a745;
        }
        .program-name {
            font-size: 1rem;
            font-weight: bold;
            color: #155724;
        }
        .admission-number {
            font-size: 1rem;
            font-weight: bold;
            color: #e74c3c;
            background: #fff;
            padding: 2px 8px;
            border-radius: 20px;
            border: 2px solid #e74c3c;
        }
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 7px 12px;
            margin: 11px 0;
            border-radius: 2px;
            border-left: 5px solid #f39c12;
            font-size: 0.88rem;
            line-height: 1.3;
        }
        .important-note::before {
            content: '⚠️ IMPORTANT: ';
            font-weight: bold;
            color: #d68910;
        }
        .undertaking-section {
            background: #f8f9fa;
            border: 2px solid #2c5aa0;
            padding: 8px 12px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .undertaking-title {
            text-align: center;
            font-size: 1.05rem;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 9px;
            text-transform: uppercase;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 5px;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid #ecf0f1;
        }
        .signature-box {
            text-align: center;
            padding: 6px 0;
            border: 1px dashed #bdc3c7;
            border-radius: 8px;
            background: #fdfdfd;
            width: 45%;
        }
        .signature-line {
            border-bottom: 2px solid #2c3e50;
            margin: 15px auto 10px;
            width: 130px;
        }
        .signature-label {
            font-weight: bold;
            color: #2c3e50;
            font-size: 0.9rem;
        }
        .footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .registrar-section {
            color: #7f8c8d;
            font-style: italic;
            font-size: 0.9rem;
        }
        .barcode-section {
            text-align: center;
        }
        .barcode {
            margin-bottom: 3px;
        }
        .barcode-text {
            font-size: 0.85rem;
            color: #7f8c8d;
            font-family: 'Courier New', monospace;
        }
        .print-btn {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
            border: none;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
            transition: all 0.3s ease;
            margin: 8px auto 0 auto;
            display: block;
        }
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(39, 174, 96, 0.4);
        }
        @media print {
            .print-btn {
                display: none !important;
                visibility: hidden !important;
            }
        }
        p {
            font-size: 0.92rem;
            line-height: 1.35;
        }
    </style>
</head>  
<body> 
    <div class="a4-sheet">
        <div class="watermark">OFFICIAL DOCUMENT</div>
        <div class="header">
            <div class="logo">
                <img src="{$logoUrl}" alt="University Logo"> 
            </div>
            <h1>SCHOOL OF BASIC AND ADVANCED STUDIES (SOBAS)</h1>  
            <h2>Usmanu Danfodiyo University, Sokoto</h2>  
            <h3>OFFICE OF THE REGISTRAR</h3>  
        </div>
        <div class="content">
            <div class="document-title">
                2025/2026 SESSION CONFIRMATION OF ADMISSION
            </div>
            <div class="date-section">
                Date: {$currentDate}
            </div>
            <div class="student-info">
                <div class="student-name">
                    Name: {$studentName}
                </div>
            </div>
            <p style="text-align: justify; margin: 10px 0;">
                <strong>This is to confirm that the above named has been offered Provisional admission into the School of Basic and Advanced Studies, Usmanu Danfodiyo University, Sokoto for the 2025/2026 Academic Session.</strong>
            </p>
            <div class="admission-details">
                <div class="program-info">
                    <div>
                        <div class="program-name">
                            Programme: {$programme}
                        </div>
                        <div style="margin-top: 3px; color: #6c757d; font-size: 0.88rem;">One Year Programme</div>
                    </div>
                    <div class="admission-number">
                        {$applicationId}
                    </div>
                </div>
            </div>
            <p style="text-align: justify; margin: 10px 0;">
                Relevant Departments, where applicable, are requested to note and register the Candidate accordingly subject to the candidate's acceptance of the undertaking below:
            </p>
            <div class="important-note">
                Please note that your admission into the University will be subject to passing the School's examination and obtaining the University's minimum cut off marks from 2026 JAMB UTME. You will also be required to obtain a minimum of 75% attendance of lessons in all the subjects to be allowed to write examinations.
            </div>
            <div class="undertaking-section">
                <div class="undertaking-title">UNDERTAKING</div>
                <p style="text-align: justify; margin: 7px 0; font-size: 0.9rem; line-height: 1.3;">
                    <strong>I, {$studentName},</strong> the undersigned, hereby accept the provisional admission offered to me. I further accept that this offer may be withdrawn by the University within the duration of the study if it is discovered that I have not satisfied the entry requirements or that my entry qualification is otherwise than as presented at the time of registration.
                </p>
                <div class="signature-section">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">Signature of Student</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">Administrative Secretary</div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="registrar-section">
                    <div>Thank you</div>
                    <div style="margin-top: 6px;"><strong>For: Registrar</strong></div>
                </div>
                <div class="barcode-section">
                    <div class="barcode">
                        <svg width="110" height="35" xmlns="http://www.w3.org/2000/svg">
                            <rect x='5' y='10' width='2' height='20' fill='#2c3e50'/><rect x='8' y='20' width='1' height='10' fill='#2c3e50'/><rect x='10' y='7' width='2' height='23' fill='#2c3e50'/><rect x='13' y='20' width='1' height='10' fill='#2c3e50'/><rect x='15' y='10' width='2' height='20' fill='#2c3e50'/><rect x='18' y='20' width='1' height='10' fill='#2c3e50'/><rect x='20' y='13' width='2' height='17' fill='#2c3e50'/><rect x='23' y='20' width='1' height='10' fill='#2c3e50'/><rect x='25' y='13' width='2' height='17' fill='#2c3e50'/><rect x='28' y='20' width='1' height='10' fill='#2c3e50'/><rect x='30' y='13' width='2' height='17' fill='#2c3e50'/><rect x='33' y='20' width='1' height='10' fill='#2c3e50'/><rect x='35' y='8' width='2' height='22' fill='#2c3e50'/><rect x='1' y='3' width='2' height='27' fill='#2c3e50'/><rect x='40' y='3' width='2' height='27' fill='#2c3e50'/>
                        </svg>
                    </div>
                    <div class="barcode-text">{$applicationId}</div>
                </div>
            </div>
        </div>
        <button class="print-btn" onclick="window.print()">
            🖨️ Print Letter
        </button>
    </div>
</body>  
 </html>
HTML;

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Download admission letter for admitted students only
     */
    public function downloadAdmissionLetter($id)
    {
        // Check if user is authenticated and is admin
        if (!auth()->check() || auth()->user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }
        
        try {
            $application = Application::findOrFail($id);
            
            // Only allow download if status is "Admitted"
            if ($application->status !== 'Admitted') {
                return redirect()->back()->with('error', 'Admission letter is only available for admitted students.');
            }
            
            // Return the admission letter view for download/print
            return $this->admissionLetter($id);
            
        } catch (\Exception $e) {
            Log::error('Admission letter download error: ' . $e->getMessage());
            Auth::logout();
            return redirect()->route('login')->with('error', 'Session expired or unauthorized. Please log in again.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|string|in:Pending,Admitted'
            ]);

            $application = Application::findOrFail($id);
            $application->status = $request->status;
            $application->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => $application->status
            ]);

        } catch (\Exception $e) {
            Log::error('Status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }
}