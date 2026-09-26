<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        @page { margin: 22px; }
        body { margin:0; color:#203c36; font-family:DejaVu Sans, sans-serif; font-size:10px; }
        .receipt { border:1px solid #d8e3df; padding:18px; }
        .brand { padding-bottom:13px; border-bottom:2px solid #176c59; }
        .brand-name { margin:0; color:#174d41; font-size:15px; font-weight:bold; }
        .brand-subtitle { margin:4px 0 0; color:#70817c; font-size:8px; text-transform:uppercase; }
        .receipt-title { margin:16px 0 4px; color:#173b35; font-size:16px; }
        .receipt-subtitle { margin:0 0 13px; color:#71817d; font-size:9px; }
        .amount-panel { margin:12px 0; padding:12px; background:#f0f6f3; text-align:center; }
        .amount-label { color:#61736e; font-size:8px; text-transform:uppercase; }
        .amount { margin-top:5px; color:#176c59; font-size:19px; font-weight:bold; }
        table { width:100%; border-collapse:collapse; }
        td { padding:7px 0; border-bottom:1px solid #e9efed; vertical-align:top; }
        td:first-child { width:40%; color:#71817d; }
        td:last-child { color:#263e38; font-weight:bold; word-break:break-word; }
        .paid { margin-top:14px; padding:7px; border:1px solid #b6d9c7; color:#216b48; font-weight:bold; text-align:center; }
        .footer { margin-top:16px; color:#85928f; font-size:8px; text-align:center; }
    </style>
</head>
<body>
    <main class="receipt">
        <header class="brand">
            <p class="brand-name">School of Basic and Advanced Studies</p>
            <p class="brand-subtitle">Official payment receipt</p>
        </header>
        <h1 class="receipt-title">Payment Receipt</h1>
        <p class="receipt-subtitle">Keep this receipt for your records.</p>
        <section class="amount-panel">
            <div class="amount-label">Amount received</div>
            <div class="amount">{{ $payment->currency }} {{ number_format(((int) $payment->amount) / 100, 2) }}</div>
        </section>
        <table>
            <tr><td>Payment type</td><td>{{ $paymentType }}</td></tr>
            <tr><td>Applicant</td><td>{{ trim(($payment->first_name ?? '') . ' ' . ($payment->last_name ?? '')) ?: 'Applicant' }}</td></tr>
            <tr><td>Matric number</td><td>{{ $payment->matric_number ?: '—' }}</td></tr>
            <tr><td>Email</td><td>{{ $payment->email ?: '—' }}</td></tr>
            <tr><td>Payment reference</td><td>{{ $payment->reference }}</td></tr>
            <tr><td>Transaction ID</td><td>{{ $payment->transaction_id ?: '—' }}</td></tr>
            <tr><td>Date paid</td><td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:i') }}</td></tr>
        </table>
        <div class="paid">PAID</div>
        <footer class="footer">Generated electronically by the SOBAS payment system.</footer>
    </main>
</body>
</html>