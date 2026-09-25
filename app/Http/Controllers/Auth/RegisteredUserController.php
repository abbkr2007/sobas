<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Payment;
use App\Services\AcademicSessionService;
use Unicodeveloper\Paystack\Facades\Paystack;

class RegisteredUserController extends Controller
{
    // Show registration form
    public function create()
    {
        // Check if registration is open
        $registrationOpen = Setting::getSetting('registration_open', true);
        
        if (!$registrationOpen) {
            $closedMessage = Setting::getSetting('registration_closed_message', 'Application portal is currently closed.');
            return view('auth.registration-closed', ['message' => $closedMessage]);
        }

        return view('auth.register');
    }

    // Store user data temporarily before payment
    public function store(Request $request)
    {
        // Check if registration is open
        $registrationOpen = Setting::getSetting('registration_open', true);
        
        if (!$registrationOpen) {
            return back()->with('error', Setting::getSetting('registration_closed_message', 'Application is currently closed.'));
        }

        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
        ]);

        $amountInKobo = config('paystack.application_fee') + config('paystack.administration_fee');

        $request->session()->put('user_data', [
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'phone_number' => $request->phone_number,
            'email'        => $request->email,
            'amount'       => $amountInKobo
        ]);

        return redirect()->route('payment.redirectToGateway');
    }

    // Redirect user to Paystack
    public function redirectToGateway(Request $request)
    {
        $userData = $request->session()->get('user_data');

        if (!$userData) {
            return redirect('/')->with('error', 'Please complete the application details before paying.');
        }

        $subaccount = trim((string) config('paystack.administration_subaccount'));
        if (!preg_match('/^ACCT_[a-zA-Z0-9]+$/', $subaccount)) {
            return redirect('/')->with('error', 'Payments are temporarily unavailable. Please try again later.');
        }

        // Always calculate the price on the server, including for older sessions.
        $userData['amount'] = config('paystack.application_fee') + config('paystack.administration_fee');
        $request->session()->put('user_data', $userData);

        return Paystack::getAuthorizationUrl([
            'email'  => $userData['email'],
            'amount' => $userData['amount'],
            'currency' => 'NGN',
            'callback_url' => route('payment.callback'),
            'subaccount' => $subaccount,
            'transaction_charge' => config('paystack.application_fee'),
            'bearer' => 'subaccount',
        ])->redirectNow();
    }

    // Handle Paystack callback
    public function handleGatewayCallback(Request $request)
    {
        try {
            $paymentDetails = Paystack::getPaymentData();
            $userData = $request->session()->get('user_data');

            if (!$userData) {
                return redirect('/')->with('error', 'Session expired or user data missing.');
            }

            if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {

                $transaction = $paymentDetails['data'];
                if ((int) ($transaction['amount'] ?? 0) !== (int) $userData['amount']
                    || ($transaction['currency'] ?? '') !== 'NGN'
                    || strcasecmp($transaction['customer']['email'] ?? '', $userData['email']) !== 0) {
                    return redirect('/')->with('error', 'Payment details do not match your application. Please contact support.');
                }

                $session = app(AcademicSessionService::class)->current();
                if (!$session) {
                    return redirect('/')->with('error', 'Academic session is not configured. Please contact support.');
                }

                $year = substr((string) $session->start_year, -2);
                $prefix = 'MAT' . $year;
                $number = User::nextMatSerial($session->id, $prefix);
                $matId = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);

                // Generate random password
                $plainPassword = Str::random(10);

                // Create user
                $user = User::create([
                    'first_name'     => $userData['first_name'],
                    'last_name'      => $userData['last_name'],
                    'phone_number'   => $userData['phone_number'],
                    'email'          => $userData['email'],
                    'password'       => Hash::make($plainPassword),
                    'plain_password' => $plainPassword,
                    'user_type'      => 'user',
                    'mat_id'         => $matId,
                    'academic_session_id' => $session->id,
                ]);

                // Save payment
                $payment = Payment::create([
                    'user_id'        => $user->id,
                    'reference'      => $paymentDetails['data']['reference'],
                    'transaction_id' => $paymentDetails['data']['id'],
                    'amount'         => $paymentDetails['data']['amount'],
                    'currency'       => $paymentDetails['data']['currency'],
                    'status'         => $paymentDetails['data']['status'],
                ]);

                // Send registration email
                Mail::to($user->email)->send(new UserRegisteredMail($user));
                

                // Store user ID for slip page
                $request->session()->put('last_user_id', $user->id);
                $request->session()->forget('user_data');

                return redirect()->route('slip')->with('success', 'Payment successful!');
            }

            return redirect('/')->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            Log::error('Paystack callback error: '.$e->getMessage());
            return redirect('/')->with('error', 'An error occurred during registration.');
        }
    }
}
