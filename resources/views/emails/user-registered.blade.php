<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Slip</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e9f5ee;
            margin: 0;
            padding: 30px 0;
        }
        .slip {
            background: #ffffff;
            border: 2px solid #4CAF50;
            border-radius: 12px;
            padding: 30px 40px;
            width: 550px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .header h2 {
            color: #2e7d32;
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #555;
            margin: 4px 0 0;
            font-size: 14px;
        }
        .info p {
            margin: 8px 0;
            font-size: 15px;
            color: #333;
        }
        .info strong {
            color: #2e7d32;
        }
        .mat-id {
            font-size: 18px;
            color: #1018bc;
            font-weight: bold;
        }
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="slip">
        <div class="header">
            <!-- Your logo here -->
            <img src="{{ asset('images/logo.png') }}" alt="ASUU Conference Logo">
            <h2>Portal of School of Basic and Advanced Studies</h2>
            <p>Registration Confirmation Slip</p>
        </div>

        <div class="info">
            <p><strong>Full Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
            <p><strong>MAT ID:</strong> <span class="mat-id">{{ $user->mat_id }}</span></p>
            <p><strong>Password:</strong> {{ $user->plain_password }}</p>
        </div>

        <div class="footer">
            Please keep this slip safe — it will be needed to log in and attend the conference.
        </div>
    </div>
</body>
</html>
