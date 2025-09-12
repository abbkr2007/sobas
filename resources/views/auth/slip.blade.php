<h2>Payment Slip</h2>
<p>Name: {{ $user->first_name }} {{ $user->last_name }}</p>
<p>Email: {{ $user->email }}</p>
<p>Amount Paid: ₦{{ $payment->amount / 100 }}</p>
<p>Reference: {{ $payment->reference }}</p>
<p>Status: {{ $payment->status }}</p>
