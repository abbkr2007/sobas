<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Slip</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .slip {
            border: 2px solid #4CAF50;
            border-radius: 10px;
            padding: 20px;
            width: 500px;
            margin: auto;
            background: #f9f9f9;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .info p { margin: 6px 0; }
        .mat-id {
            font-size: 18px;
            color: #1018bc;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="slip">
        <div class="header">
            <h2>ASUU National Conference 2024</h2>
            <p>Registration Confirmation Slip</p>
        </div>
        <div class="info">
            <p><strong>Full Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
            <p><strong>MAT ID:</strong> <span class="mat-id">{{ $user->mat_id }}</span></p>
            <p><strong>Password:</strong> {{ $user->plain_password }}</p>
        </div>
        <p style="margin-top: 15px; text-align: center; font-size: 13px; color: #555;">
            Please keep this slip safe — it will be needed to log in and attend the conference.
        </p>
    </div>
</body>
</html>
