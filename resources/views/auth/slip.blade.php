<x-guest-layout>
    <section class="slip-content py-10">
        <div class="container mx-auto max-w-lg bg-white border-2 border-green-600 rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-green-700 mb-1">Payment Slip</h2>
                <p class="text-gray-600 text-sm">Keep this for your records</p>
            </div>

            <!-- User Info -->
            <div class="space-y-2 text-gray-700">
                <p><span class="font-semibold">Name:</span> {{ $user->first_name }} {{ $user->last_name }}</p>
                <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
                <p><span class="font-semibold">Phone:</span> {{ $user->phone_number ?? 'N/A' }}</p>
                <p><span class="font-semibold">Amount Paid:</span> ₦{{ number_format($payment->amount / 100, 2) }}</p>
                <p><span class="font-semibold">Reference:</span> {{ $payment->reference }}</p>
                <p><span class="font-semibold">Status:</span> 
                    <span class="{{ $payment->status == 'success' ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </p>
                <p><span class="font-semibold">Date:</span> {{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y H:i') }}</p>
            </div>

            <!-- Print Button -->
            <div class="text-center mt-6">
                <button onclick="window.print()" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                    Print Slip
                </button>
            </div>

            <!-- Footer -->
            <div class="mt-4 text-xs text-gray-400 text-center">
                This is a system-generated payment slip.
            </div>
        </div>
    </section>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .slip-content, .slip-content * {
                visibility: visible;
            }
            .slip-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</x-guest-layout>
