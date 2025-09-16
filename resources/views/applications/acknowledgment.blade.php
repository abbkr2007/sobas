<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bio Data Form - {{ $application->application_id }}</title>
    <style>
        /* ===== BASE ===== */
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }
        .container {
            width: 90%;
            margin: 15px auto;
            padding: 15px 20px;
            border: 1.5px solid #4CAF50;
            border-radius: 6px;
        }

        /* ===== HEADER ===== */
        .header { text-align: center; margin-bottom: 12px; position: relative; }
        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 3px;
        }
        .subtitle { font-size: 11px; color: #555; margin-bottom: 3px; }
        .fullname {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        /* ===== PHOTO ===== */
        .photo {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            border: 1px solid #4CAF50;
            object-fit: cover;
            border-radius: 4px;
        }

        /* ===== SECTIONS ===== */
        .section {
            margin-bottom: 12px;
            border: 1px solid #4CAF50;
            border-radius: 4px;
            padding: 8px;
        }
        .section h3 {
            background-color: #4CAF50;
            color: #fff;
            padding: 4px;
            margin: -8px -8px 8px -8px;
            border-radius: 4px 4px 0 0;
            font-size: 12px;
            text-align: center;
        }

        /* ===== PERSONAL DETAILS ===== */
        .personal-table {
            width: 100%;
            border-collapse: collapse;
        }
        .personal-table td {
            width: 50%;
            padding: 4px 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .personal-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* ===== DETAILS TABLE ===== */
        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .details th, .details td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            vertical-align: top;
            font-size: 10px;
        }
        .details tr:nth-child(even) {
            background-color: #f6fff6;
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            color: #4CAF50;
            font-size: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <!-- Logo on the left -->
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
        <!-- Applicant Photo on the right -->
       
      
       <img src="{{ public_path('images/' . basename($application->photo)) }}" class="photo" alt="Photo">
       





        <div class="title">Bio Data Form</div>
        <div class="subtitle">Application ID: <strong>{{ $application->application_id }}</strong></div>
        <div class="fullname">Full Name: <strong>{{ $application->surname }} {{ $application->firstname }} {{ $application->middlename }}</strong></div>
    </div>

    <!-- Personal Details -->
    <div class="section">
        <h3>Personal Details</h3>
        <table class="personal-table">
            <tr>
                <td><strong>Email:</strong> {{ $application->email }}</td>
                <td><strong>Phone:</strong> {{ $application->phone }}</td>
            </tr>
            <tr>
                <td><strong>Date of Birth:</strong> {{ $application->dob }}</td>
                <td><strong>Place of Birth:</strong> {{ $application->place_of_birth }}</td>
            </tr>
            <tr>
                <td><strong>Gender:</strong> {{ $application->gender }}</td>
                <td><strong>State:</strong> {{ $application->state }}</td>
            </tr>
            <tr>
                <td><strong>LGA:</strong> {{ $application->lga }}</td>
                <td><strong>Town:</strong> {{ $application->town }}</td>
            </tr>
            <tr>
                <td><strong>Country:</strong> {{ $application->country }}</td>
                <td><strong>Foreign Country:</strong> {{ $application->foreign_country }}</td>
            </tr>
            <tr>
                <td><strong>Home Address:</strong> {{ $application->home_address }}</td>
                <td><strong>Application Type:</strong> {{ $application->application_type }}</td>
            </tr>
            <tr>
                <td><strong>Guardian:</strong> {{ $application->guardian }}</td>
                <td><strong>Guardian Phone:</strong> {{ $application->guardian_phone }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Guardian Address:</strong> {{ $application->guardian_address }}</td>
            </tr>
        </table>
    </div>

    <!-- Schools + Exams -->
    <div class="section">
        <h3>Schools Attended & Examinations</h3>

        <!-- Schools -->
        <div class="details" style="margin-bottom: 10px;">
            <table>
                <tr>
                    <th>#</th>
                    <th>School Name</th>
                    <th>From</th>
                    <th>To</th>
                </tr>
                @foreach($application->schools as $index => $school)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $school['name'] }}</td>
                        <td>{{ $school['from'] }}</td>
                        <td>{{ $school['to'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <!-- Exam 1 -->
        @if($application->first_exam_type)
        <div class="details" style="margin-bottom: 10px;">
            <table>
                <tr><th colspan="2" style="background:#4CAF50; color:#fff;">Exam: {{ $application->first_exam_type }} ({{ $application->first_exam_year }})</th></tr>
                <tr><th>Exam Number</th><td>{{ $application->first_exam_number }}</td></tr>
                <tr><th>Center Number</th><td>{{ $application->first_center_number }}</td></tr>
            </table>
            <table>
                <tr><th>Subject</th><th>Grade</th></tr>
                @foreach($application->first_subjects as $i => $subject)
                    <tr>
                        <td>{{ $subject }}</td>
                        <td>{{ $application->first_grades[$i] ?? '' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @endif

        <!-- Exam 2 -->
        @if($application->second_exam_type)
        <div class="details">
            <table>
                <tr><th colspan="2" style="background:#4CAF50; color:#fff;">Exam: {{ $application->second_exam_type }} ({{ $application->second_exam_year }})</th></tr>
                <tr><th>Exam Number</th><td>{{ $application->second_exam_number }}</td></tr>
                <tr><th>Center Number</th><td>{{ $application->second_center_number }}</td></tr>
            </table>
            <table>
                <tr><th>Subject</th><th>Grade</th></tr>
                @foreach($application->second_subjects as $i => $subject)
                    <tr>
                        <td>{{ $subject }}</td>
                        <td>{{ $application->second_grades[$i] ?? '' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        Thank you for your submission!
    </div>
</div>
</body>
</html>
