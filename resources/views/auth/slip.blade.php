<x-guest-layout>
<section style="padding: 20px; display: flex; justify-content: center; font-family: Arial, sans-serif;">
    <div style="border: 1.5px solid #28a745; padding: 20px; max-width: 600px; width: 100%; border-radius: 10px; background-color: #f9fff9; box-sizing: border-box;">

        <!-- Logo & Header -->
        <div style="text-align: center; margin-bottom: 15px;">
            <a href="{{ route('dashboard') }}" class="d-inline-block mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Site Logo" width="120">
            </a>
            <h2 style="color: #28a745; margin: 0; font-size: 24px; font-weight: bold;">Payment Slip</h2>
            <p style="margin: 3px 0 0; font-size: 12px; color: #555;">Official receipt for your payment</p>
            <hr style="margin-top: 10px; border-top: 1.5px solid #28a745;">
        </div>

        <!-- User Info Table -->
        <div style="overflow-x: auto; width: 100%; max-width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; max-width: 100%;">
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; width: 35%; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Name</td>
                    <td style="border: 1px solid #28a745; padding: 8px; width: 65%; word-wrap: break-word; overflow-wrap: break-word; max-width: 0; font-size: 14px;">{{ $user->first_name }} {{ $user->last_name }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Email</td>
                    <td style="border: 1px solid #28a745; padding: 8px; word-wrap: break-word; overflow-wrap: break-word; max-width: 0; font-size: 12px; line-height: 1.3;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Phone</td>
                    <td style="border: 1px solid #28a745; padding: 8px; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">{{ $user->phone_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Amount Paid</td>
                    <td style="border: 1px solid #28a745; padding: 8px; color: #28a745; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">₦{{ number_format($payment->amount / 100, 2) }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Payment Ref</td>
                    <td style="border: 1px solid #28a745; padding: 8px; word-wrap: break-word; overflow-wrap: break-word; max-width: 0; font-size: 11px; font-family: monospace;">{{ $payment->reference }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Matric Number</td>
                    <td style="border: 1px solid #28a745; padding: 8px; word-wrap: break-word; overflow-wrap: break-word; max-width: 0; font-weight: bold; color: #28a745; font-family: monospace;">{{ $user->mat_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Password</td>
                    <td style="border: 1px solid #28a745; padding: 8px; word-wrap: break-word; overflow-wrap: break-word; max-width: 0; font-weight: bold; color: #28a745; font-family: monospace;">{{ $user->plain_password ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Status</td>
                    <td style="border: 1px solid #28a745; padding: 8px; color: {{ $payment->status == 'success' ? '#28a745' : 'red' }}; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">
                        {{ ucfirst($payment->status) }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #28a745; padding: 8px; font-weight: bold; word-wrap: break-word; overflow-wrap: break-word; max-width: 0;">Date</td>
                    <td style="border: 1px solid #28a745; padding: 8px; word-wrap: break-word; overflow-wrap: break-word; max-width: 0; font-size: 13px;">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <!-- Buttons -->
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" 
                    style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-right: 10px;">
                Print Slip
            </button>
            <a href="{{ route('auth.signin') }}" 
               style="background-color: #fff; color: #28a745; border: 2px solid #28a745; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                Fill Form
            </a>
        </div>

        <!-- Footer -->
        <div style="text-align: center; font-size: 10px; color: #555; margin-top: 15px;">
            This is a system-generated payment slip. Keep it safe for your records.
        </div>
    </div>
</section>

<style>
    /* Force text wrapping and container fitting */
    * {
        box-sizing: border-box;
    }
    
    table {
        table-layout: fixed !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    td {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        max-width: 0 !important;
        white-space: normal !important;
    }
    
    /* Responsive styles for slip page */
    @media (max-width: 768px) {
        section {
            padding: 8px !important;
        }
        
        div[style*="border: 1.5px solid #28a745"] {
            padding: 12px !important;
            max-width: 100% !important;
            margin: 0 auto !important;
        }
        
        table {
            font-size: 12px !important;
        }
        
        td {
            padding: 6px 4px !important;
            font-size: 12px !important;
        }
        
        h2 {
            font-size: 18px !important;
        }
        
        img {
            width: 70px !important;
        }
        
        /* Force smaller text for long content */
        td:nth-child(2) {
            font-size: 11px !important;
            line-height: 1.2 !important;
        }
    }
    
    @media (max-width: 480px) {
        section {
            padding: 5px !important;
        }
        
        div[style*="border: 1.5px solid #28a745"] {
            padding: 8px !important;
            margin: 0 !important;
        }
        
        table {
            font-size: 11px !important;
        }
        
        td {
            padding: 4px 3px !important;
            font-size: 10px !important;
        }
        
        h2 {
            font-size: 16px !important;
        }
        
        img {
            width: 50px !important;
        }
        
        /* Even smaller text for very small screens */
        td:nth-child(2) {
            font-size: 9px !important;
            line-height: 1.1 !important;
        }
        
        /* Shorter labels for mobile */
        td[style*="font-weight: bold"]:contains("Payment Reference")::after {
            content: "Ref";
        }
    }
    
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
            padding: 15px !important;
        }
        button, a {
            display: none;
        }
        
        div[style*="border: 1.5px solid #28a745"] {
            max-width: 100% !important;
            padding: 15px !important;
        }
        
        table {
            font-size: 12px !important;
        }
        
        td {
            padding: 5px !important;
        }
    }
</style>
</x-guest-layout>
