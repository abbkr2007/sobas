<x-guest-layout>
    <section style="padding: 40px; display: flex; justify-content: center; font-family: Arial, sans-serif;">
        <div style="border: 3px solid #28a745; padding: 30px; max-width: 500px; width: 100%; border-radius: 10px; background-color: #f9fff9;">
            
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="color: #28a745; margin: 0; font-size: 26px; font-weight: bold;">Payment Slip</h2>
                <p style="margin: 5px 0 0; font-size: 12px; color: #555;">Official receipt for your payment</p>
                <hr style="margin-top: 15px; border-top: 2px solid #28a745;">
            </div>

            <!-- User Info Table -->
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; width: 40%;">Name</td>
                    <td style="border: 1px solid #28a745; padding: 8px;">{{ $user->first_name }} {{ $user->last_name }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold;">Email</td>
                    <td style="border: 1px solid #28a745; padding: 8px;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold;">Phone</td>
                    <td style="border: 1px solid #28a745; padding: 8px;">{{ $user->phone_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold;">Amount Paid</td>
                    <td style="border: 1px solid #28a745; padding: 8px; color: #28a745; font-weight: bold;">₦{{ number_format($payment->amount / 100, 2) }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold;">Reference</td>
                    <td style="border: 1px solid #28a745; padding: 8px;">{{ $payment->reference }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold;">Status</td>
                    <td style="border: 1px solid #28a745; padding: 8px; color: {{ $payment->status == 'success' ? '#28a745' : 'red' }}; font-weight: bold;">
                        {{ ucfirst($payment->status) }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold;">Date</td>
                    <td style="border: 1px solid #28a745; padding: 8px;">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y H:i') }}</td>
                </tr>
            </table>

            <!-- Buttons -->
            <div style="text-align: center; margin-top: 25px;">
                <button onclick="window.print()" 
                        style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-right: 10px;">
                    Print Slip
                </button>
                <a href="{{ route('auth.signin') }}" 
                   style="background-color: #fff; color: #28a745; border: 2px solid #28a745; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                    Login
                </a>
            </div>

            <!-- Footer -->
            <div style="text-align: center; font-size: 10px; color: #555; margin-top: 20px;">
                This is a system-generated payment slip. Keep it safe for your records.
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
            button, a {
                display: none;
            }
        }
    </style>
</x-guest-layout>
