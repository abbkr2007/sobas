<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ConfirmationFeePayment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Unicodeveloper\Paystack\Facades\Paystack;

class ConfirmationPaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();
        $application = Application::where('application_id', $user->mat_id)->firstOrFail();

        if ($application->status !== 'Admitted') {
            return redirect()->route('dashboard')->with('error', 'Confirmation fees can only be paid after admission.');
        }

        if (ConfirmationFeePayment::where('application_id', $application->id)->where('status', 'success')->exists()) {
            return redirect()->route('dashboard')->with('success', 'Your confirmation fee is already paid.');
        }

        $confirmationFee = (int) Setting::getSetting('confirmation_fee', 1000000);
        $administrationFee = (int) Setting::getSetting('administration_fee', config('paystack.administration_fee'));
        $amount = $confirmationFee + $administrationFee;
        $reference = 'CONF-' . Str::uuid()->toString();
        $payment = ConfirmationFeePayment::create([
            'application_id' => $application->id,
            'user_id' => $user->id,
            'reference' => $reference,
            'amount' => $amount,
            'currency' => 'NGN',
            'status' => 'pending',
        ]);

        $request->session()->put('confirmation_payment', [
            'payment_id' => $payment->id,
            'reference' => $reference,
            'user_id' => $user->id,
        ]);

        return Paystack::getAuthorizationUrl([
            'email' => $application->email,
            'amount' => $amount,
            'currency' => 'NGN',
            'reference' => $reference,
            'callback_url' => route('confirmation-payment.callback'),
        ])->redirectNow();
    }

    public function callback(Request $request)
    {
        $pending = $request->session()->get('confirmation_payment');
        if (!$pending || empty($pending['payment_id']) || empty($pending['reference']) || empty($pending['user_id'])) {
            return redirect()->route('dashboard')->with('error', 'Payment session expired. Please try again.');
        }

        $payment = ConfirmationFeePayment::with('application')
            ->whereKey($pending['payment_id'])
            ->where('user_id', $pending['user_id'])
            ->where('reference', $pending['reference'])
            ->first();

        if (!$payment) {
            return redirect()->route('dashboard')->with('error', 'Payment record could not be verified.');
        }

        try {
            $details = Paystack::getPaymentData();
            $transaction = $details['data'] ?? [];
            $valid = !empty($details['status'])
                && ($transaction['status'] ?? null) === 'success'
                && hash_equals($payment->reference, (string) ($transaction['reference'] ?? ''))
                && (int) ($transaction['amount'] ?? 0) === (int) $payment->amount
                && ($transaction['currency'] ?? '') === $payment->currency
                && strcasecmp((string) ($transaction['customer']['email'] ?? ''), (string) $payment->application->email) === 0;

            if (!$valid) {
                return redirect()->route('dashboard')->with('error', 'Payment could not be verified. Please try again.');
            }

            if ($payment->status !== 'success') {
                $payment->update([
                    'transaction_id' => (string) $transaction['id'],
                    'status' => 'success',
                ]);
            }

            $request->session()->forget('confirmation_payment');
            return redirect()->route('dashboard')->with('success', 'Payment completed successfully.');
        } catch (\Throwable $exception) {
            report($exception);
            return redirect()->route('dashboard')->with('error', 'Payment could not be verified. Please contact support if you were charged.');
        }
    }
}