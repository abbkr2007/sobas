<x-guest-layout>
    <section style="padding: 30px; display: flex; justify-content: center;">
        <div style="border: 2px solid green; padding: 20px; max-width: 500px; width: 100%; border-radius: 8px;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="color: green; margin: 0;">Payment Slip</h2>
                <p style="margin: 0; font-size: 12px; color: gray;">Keep this for your records</p>
            </div>

            <!-- User Info -->
            <div style="line-height: 1.6; color: #333;">
                <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
                <p><strong>Amount Paid:</strong> ₦{{ number_format($payment->amount / 100, 2) }}</p>
                <p><strong>Reference:</strong> {{ $payment->reference }}</p>
                <p><strong>Status:</strong> 
                    <span style="color: {{ $payment->status == 'success' ? 'green' : 'red' }}; font-weight: bold;">
                        {{ ucfirst($payment->status) }}
                    </span>
                </p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y H:i') }}</p>
            </div>

            <!-- Print Button -->
            <div style="text-align: center; margin-top: 20px;">
                <button onclick="window.print()" 
                        style="background-color: green; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">
                    Print Slip
                </button>
            </div>

            <div style="text-align: center; font-size: 10px; color: gray; margin-top: 10px;">
                This is a system-generated payment slip.
            </div>
        </div>
    </section>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            section, section * {
                visibility: visible;
            }
            section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</x-guest-layout>
