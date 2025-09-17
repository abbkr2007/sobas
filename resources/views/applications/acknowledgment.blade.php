<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bio Data Form - {{ $application->application_id }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0; padding: 0; color: #333; font-size: 11px;
        }
        .container {
            width: 90%; max-width: 800px;
            margin: 15px auto; padding: 15px 20px;
            border: 1.5px solid #4CAF50; border-radius: 6px;
        }

        /* ===== HEADER ===== */
        .header {
            position: relative;
            min-height: 120px; /* fixed header height */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .header-center {
            text-align: center;
            max-width: 600px;
        }
        .logo {
            width: 80px; height: 80px;
            object-fit: contain;
            display: block; margin: 0 auto 5px auto;
        }
        .title {
            font-size: 20px; font-weight: bold; color: #4CAF50; margin: 2px 0;
        }
        .subtitle {
            font-size: 12px; font-weight: bold; color: #555; margin: 2px 0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .fullname {
            font-size: 14px; font-weight: bold; margin: 2px 0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* ===== PHOTO ===== */
        .photo {
            width: 100px; height: 100px;
            border: 1px solid #4CAF50; object-fit: cover; border-radius: 4px;
            position: absolute; top: 0; right: 0;
        }

        /* ===== SECTIONS ===== */
        .section { margin-bottom: 12px; border: 1px solid #4CAF50; border-radius: 4px; padding: 8px; }
        .section h3 {
            background: #4CAF50; color: #fff; padding: 4px;
            margin: -8px -8px 8px -8px; border-radius: 4px 4px 0 0;
            font-size: 13px; text-align: center; font-weight: bold;
        }

        /* ===== TABLES ===== */
        .personal-table, .details table { width: 100%; border-collapse: collapse; }
        .personal-table td, .details th, .details td { border: 1px solid #ddd; padding: 5px; vertical-align: top; }
        .personal-table tr:nth-child(even) { background: #f9f9f9; }
        .details tr:nth-child(even) { background-color: #f6fff6; }

        /* ===== FOOTER ===== */
        .footer { text-align: center; margin-top: 10px; font-weight: bold; color: #4CAF50; font-size: 11px; }

        /* ===== PRINT A4 ===== */
        @page { size: A4 portrait; margin: 15mm; }
        @media print {
            body { margin: 0; padding: 0; }
            .container { width: 100%; border: none; border-radius: 0; }
            .header, .section, .footer { page-break-inside: avoid; }
            .print-btn { display: none; }
        }

        /* ===== PRINT BUTTON ===== */
        .print-btn {
            display: block; width: 140px; margin: 10px auto 20px auto;
            padding: 8px 12px; font-size: 14px; background: #4CAF50; color: #fff;
            border: none; border-radius: 4px; cursor: pointer;
        }
        .print-btn:hover { background: #45a049; }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print Bio Data</button>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-center">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
                <div class="title">Bio Data Form</div>
                <div class="subtitle">Application ID: <strong>{{ $application->application_id }}</strong></div>
                <div class="fullname">Full Name: <strong>{{ $application->surname }} {{ $application->firstname }} {{ $application->middlename }}</strong></div>
            </div>
            <img src="{{ asset($application->photo) }}" class="photo" alt="Photo">

        </div>

        <!-- Personal Details -->
        <div class="section">
            <h3>Personal Details</h3>
            <table class="personal-table">
                <tr><td><strong>Email:</strong> {{ $application->email }}</td><td><strong>Phone:</strong> {{ $application->phone }}</td></tr>
                <tr><td><strong>Date of Birth:</strong> {{ $application->dob }}</td><td><strong>Place of Birth:</strong> {{ $application->place_of_birth }}</td></tr>
                <tr><td><strong>Gender:</strong> {{ $application->gender }}</td><td><strong>State:</strong> {{ $application->state }}</td></tr>
                <tr><td><strong>LGA:</strong> {{ $application->lga }}</td><td><strong>Town:</strong> {{ $application->town }}</td></tr>
                <tr><td><strong>Country:</strong> {{ $application->country }}</td><td><strong>Foreign Country:</strong> {{ $application->foreign_country }}</td></tr>
                <tr><td><strong>Home Address:</strong> {{ $application->home_address }}</td><td><strong>Application Type:</strong> {{ $application->application_type }}</td></tr>
                <tr><td><strong>Guardian:</strong> {{ $application->guardian }}</td><td><strong>Guardian Phone:</strong> {{ $application->guardian_phone }}</td></tr>
                <tr><td colspan="2"><strong>Guardian Address:</strong> {{ $application->guardian_address }}</td></tr>
            </table>
        </div>

        <!-- Schools + Exams -->
        <div class="section">
            <h3>Schools Attended & Examinations</h3>
            <div class="details" style="margin-bottom:10px;">
                <table>
                    <tr><th>#</th><th>School Name</th><th>From</th><th>To</th></tr>
                    @foreach($application->schools as $i=>$school)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $school['name'] }}</td>
                        <td>{{ $school['from'] }}</td>
                        <td>{{ $school['to'] }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>

            @if($application->first_exam_type)
            <div class="details" style="margin-bottom:10px;">
                <table>
                    <tr><th colspan="2" style="background:#4CAF50;color:#fff;">Exam: {{ $application->first_exam_type }} ({{ $application->first_exam_year }})</th></tr>
                    <tr><th>Exam Number</th><td>{{ $application->first_exam_number }}</td></tr>
                    <tr><th>Center Number</th><td>{{ $application->first_center_number }}</td></tr>
                </table>
                <table>
                    <tr><th>Subject</th><th>Grade</th></tr>
                    @foreach($application->first_subjects as $i=>$sub)
                    <tr><td>{{ $sub }}</td><td>{{ $application->first_grades[$i] ?? '' }}</td></tr>
                    @endforeach
                </table>
            </div>
            @endif

            @if($application->second_exam_type)
            <div class="details">
                <table>
                    <tr><th colspan="2" style="background:#4CAF50;color:#fff;">Exam: {{ $application->second_exam_type }} ({{ $application->second_exam_year }})</th></tr>
                    <tr><th>Exam Number</th><td>{{ $application->second_exam_number }}</td></tr>
                    <tr><th>Center Number</th><td>{{ $application->second_center_number }}</td></tr>
                </table>
                <table>
                    <tr><th>Subject</th><th>Grade</th></tr>
                    @foreach($application->second_subjects as $i=>$sub)
                    <tr><td>{{ $sub }}</td><td>{{ $application->second_grades[$i] ?? '' }}</td></tr>
                    @endforeach
                </table>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">Thank you for your submission!</div>
    </div>
</body>
</html>
