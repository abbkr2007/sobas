<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admission Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f6fff6;
            margin: 0;
            padding: 30px 0;
        }
        .admission {
            background: #ffffff;
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 30px 40px;
            width: 550px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #28a745;
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
            color: #218838;
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
            color: #218838;
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
    <div class="admission">
        <div class="header">
            <img src="https://sobas.cloud/images/logo.png" alt="SOBAS Logo">
            <h2>School of Basic and Advanced Studies</h2>
            <p>Admission Notification</p>
        </div>
        <div class="info">
            <p>Dear <strong>{{ $application->firstname }} {{ $application->middlename }} {{ $application->surname }}</strong>,</p>
            <p>Congratulations! You have been <strong>admitted</strong> to the following programme:</p>
            <p><strong>Programme:</strong> {{ $application->application_type }}</p>
            <p><strong>Application ID:</strong> {{ $application->application_id }}</p>
            <p><strong>Email:</strong> {{ $application->email }}</p>
            <p><strong>Phone:</strong> {{ $application->phone }}</p>
            <p><strong>Date of Admission:</strong> {{ now()->format('F d, Y') }}</p>
        </div>
        <div class="footer">
            Please log in to your portal to download your admission letter and complete your registration.<br>
            Welcome to the School of Basic and Advanced Studies!
        </div>
    </div>
</body>
</html>
