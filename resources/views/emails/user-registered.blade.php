<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Slip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body { font-family: Arial, sans-serif; background:#f9fff9; margin:0; padding:0; }
        .container { max-width:600px; margin:40px auto; padding:25px; border:1.5px solid #28a745; border-radius:10px; background:#fff; }
        .header { text-align:center; margin-bottom:20px; }
        .header img { width:100px; }
        .header h2 { color:#28a745; margin:10px 0 0; }
        table { width:100%; border-collapse:collapse; margin-top:15px; }
        table td { border:1px solid #e6f4ea; padding:10px; vertical-align:top; }
        table td.label { font-weight:700; width:40%; background:#f1fbf2; }
        .btn { display:inline-block; padding:10px 20px; border-radius:5px; font-weight:700; text-decoration:none; margin-top:20px; }
        .btn-login { background:#28a745; color:#fff; }
        .footer { font-size:12px; color:#666; text-align:center; margin-top:15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="ASUU Logo">
            </a>
            <h2>Registration Slip</h2>
            <p>ASUU National Conference 2024</p>
        </div>

        <p>Dear {{ $user->first_name }} {{ $user->last_name }},</p>
        <p>Thank you for registering. Below are your registration and payment details:</p>

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
                <td class="label">Matric Number</td>
                <td>{{ $user->mat_id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Password</td>
                <td>{{ $user->plain_password ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Amount Paid</td>
                <td style="color:#28a745; font-weight:700;">₦{{ number_format($payment->amount / 100, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Payment Reference</td>
                <td>{{ $payment->reference }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td style="font-weight:700; color:{{ $payment->status === 'success' ? '#28a745' : 'red' }}">
                    {{ ucfirst($payment->status) }}
                </td>
            </tr>
            <tr>
                <td class="label">Date</td>
                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y H:i') }}</td>
            </tr>
        </table>

        <div style="text-align:center;">
            <a href="{{ url('/auth/signin') }}" class="btn btn-login">Login to your account</a>
        </div>

        <div class="footer">
            This is an automated registration slip. Keep it safe for the conference.
        </div>
    </div>
</body>
</html> -->
