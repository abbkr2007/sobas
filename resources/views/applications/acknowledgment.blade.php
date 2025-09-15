<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acknowledgment Slip - {{ $application->application_id }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #333; }
        .container { width: 90%; margin: auto; padding: 20px; border: 1px solid #ddd; }
        .header { text-align: center; margin-bottom: 30px; }
        .header img { width: 100px; height: auto; margin-bottom: 10px; }
        .title { font-size: 28px; font-weight: bold; color: #1018bc; margin-bottom: 5px; }
        .subtitle { font-size: 16px; color: #555; margin-bottom: 10px; }
        .section { margin-bottom: 25px; border: 1px solid #1018bc; border-radius: 5px; padding: 10px; }
        .section h3 { background-color: #1018bc; color: #fff; padding: 8px; margin: -10px -10px 10px -10px; border-radius: 5px 5px 0 0; }
        .details table { width: 100%; border-collapse: collapse; }
        .details th, .details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .details tr:nth-child(even) { background-color: #f9f9f9; }
        .photo { float: right; width: 120px; height: 140px; border: 1px solid #ddd; margin-left: 15px; }
        .clearfix { clear: both; }
        .signature { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature div { text-align: center; width: 45%; }
        .signature-line { border-top: 1px solid #000; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo">
            <div class="title">Application Acknowledgment Slip</div>
            <div class="subtitle">Application ID: <strong>{{ $application->application_id }}</strong></div>
        </div>

        <!-- Personal Details -->
        <div class="section">
            <h3>Personal Details</h3>
            @if($application->photo)
                <img src="{{ public_path('storage/'.$application->photo) }}" class="photo" alt="Photo">
            @endif
            <div class="details">
                <table>
                    <tr><th>Surname</th><td>{{ $application->surname }}</td></tr>
                    <tr><th>Firstname</th><td>{{ $application->firstname }}</td></tr>
                    <tr><th>Middlename</th><td>{{ $application->middlename }}</td></tr>
                    <tr><th>Email</th><td>{{ $application->email }}</td></tr>
                    <tr><th>Phone</th><td>{{ $application->phone }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $application->dob }}</td></tr>
                    <tr><th>Place of Birth</th><td>{{ $application->place_of_birth }}</td></tr>
                    <tr><th>Gender</th><td>{{ $application->gender }}</td></tr>
                    <tr><th>State</th><td>{{ $application->state }}</td></tr>
                    <tr><th>LGA</th><td>{{ $application->lga }}</td></tr>
                    <tr><th>Town</th><td>{{ $application->town }}</td></tr>
                    <tr><th>Country</th><td>{{ $application->country }}</td></tr>
                    <tr><th>Foreign Country</th><td>{{ $application->foreign_country }}</td></tr>
                    <tr><th>Home Address</th><td>{{ $application->home_address }}</td></tr>
                    <tr><th>Guardian</th><td>{{ $application->guardian }}</td></tr>
                    <tr><th>Guardian Address</th><td>{{ $application->guardian_address }}</td></tr>
                    <tr><th>Guardian Phone</th><td>{{ $application->guardian_phone }}</td></tr>
                    <tr><th>Application Type</th><td>{{ $application->application_type }}</td></tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Schools Attended -->
        <div class="section">
            <h3>Schools Attended</h3>
            <div class="details">
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
        </div>

        <!-- Exam Results -->
        <div class="section">
            <h3>Examinations</h3>
            <div class="details">
                <table>
                    <tr>
                        <th>Exam Type</th>
                        <th>Year</th>
                        <th>Number</th>
                        <th>Center</th>
                        <th>Subjects & Grades</th>
                    </tr>
                    @if($application->first_exam_type)
                    <tr>
                        <td>{{ $application->first_exam_type }}</td>
                        <td>{{ $application->first_exam_year }}</td>
                        <td>{{ $application->first_exam_number }}</td>
                        <td>{{ $application->first_center_number }}</td>
                        <td>
                            @foreach($application->first_subjects as $i => $subject)
                                {{ $subject }}: {{ $application->first_grades[$i] ?? '' }}<br>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    @if($application->second_exam_type)
                    <tr>
                        <td>{{ $application->second_exam_type }}</td>
                        <td>{{ $application->second_exam_year }}</td>
                        <td>{{ $application->second_exam_number }}</td>
                        <td>{{ $application->second_center_number }}</td>
                        <td>
                            @foreach($application->second_subjects as $i => $subject)
                                {{ $subject }}: {{ $application->second_grades[$i] ?? '' }}<br>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature">
            <div>
                <div class="signature-line"></div>
                <span>Applicant Signature</span>
            </div>
            <div>
                <div class="signature-line"></div>
                <span>Officer Signature</span>
            </div>
        </div>

        <div style="text-align:center; margin-top:30px; font-weight:bold;">
            Thank you for your submission!
        </div>
    </div>
</body>
</html>
