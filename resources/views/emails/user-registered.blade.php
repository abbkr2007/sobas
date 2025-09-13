<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            background-color: #f9fff9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 25px;
            border: 1.5px solid #28a745;
            border-radius: 10px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 120px;
        }
        .header h2 {
            color: #28a745;
            margin: 10px 0 0 0;
            font-size: 24px;
        }
        .header p {
            font-size: 12px;
            color: #555;
            margin: 3px 0 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table td {
            border: 1px solid #28a745;
            padding: 8px;
        }
        table td.label {
            font-weight: bold;
            width: 40%;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn-login {
            background-color: #28a745;
            color: white;
        }
        .footer {
            font-size: 10px;
            color: #555;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header / Logo -->
        <div class="header">
            <a href="https://sobas.cloud/auth/signin">
                <img src="{{ asset('images/logo.png') }}" alt="Site Logo">
            </a>
            <h2>Payment Slip</h2>
            <p>Official receipt for your payment</p>
        </div>

        <!-- Greeting -->
        <p>Dear {{ $user->first_name }} {{ $user->last_name }},</p>
        <p>Thank you for your payment. Below are the details of your transaction and account:</p>

        <!-- User Info Table -->
        <table>
            <tr>
                <td class="label">Name</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td class="label">Phone</td>
                <td>{{ $user->phone_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Amount Paid</td>
                <td style="color: #28a745; font-weight: bold;">₦{{ number_format($payment->amount / 100, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Payment Reference</td>
                <td>{{ $payment->reference }}</td>
            </tr>
            <tr>
                <td class="label">Matric Number</td>
                <td>{{ $user->mat_id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Password</td>
                <td>{{ $user->plain_password ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td style="color: {{ $payment->status == 'success' ? '#28a745' : 'red' }}; font-weight: bold;">
                    {{ ucfirst($payment->status) }}
                </td>
            </tr>
            <tr>
                <td class="label">Date</td>
                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y H:i') }}</td>
            </tr>
        </table>

        <!-- Login Button -->
        <div style="text-align: center;">
            <a href="https://sobas.cloud/auth/signin" class="btn btn-login">Login to your account</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            This is a system-generated payment slip. Keep it safe for your records.
        </div>
    </div>
</body>
</html>
