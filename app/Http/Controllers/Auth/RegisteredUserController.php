<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Payment;
use Paystack;

class RegisteredUserController extends Controller
{
    // Show registration form
    public function create()
    {
        return view('auth.register');
    }

    // Store user data temporarily before payment
    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
        ]);

        $amountInKobo = 630000; // ₦6,300 -> Paystack expects kobo

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
            return redirect()->route('register')->with('error', 'Session expired. Please re-enter your details.');
        }

        return Paystack::getAuthorizationUrl([
            'email'  => $userData['email'],
            'amount' => $userData['amount'],
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

            if (!empty($paymentDetails['status']) && $paymentDetails['data']['status'] === 'success') {

                // Generate MAT ID
                $year = date('y'); // last 2 digits
                $prefix = 'MAT'.$year;
                $lastUser = User::where('mat_id', 'like', $prefix.'%')
                                ->orderBy('id', 'desc')
                                ->first();
                $number = $lastUser ? (int) substr($lastUser->mat_id, 5) + 1 : 1;
                $matId = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);

                // Generate random password (temporary)
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

                // Send registration email (pass both user and payment)
                Mail::to($user->email)->send(new UserRegisteredMail($user, $payment));

                // Store user ID for slip page
                $request->session()->put('last_user_id', $user->id);
                $request->session()->forget('user_data');

                return redirect()->route('slip')->with('success', 'Payment successful!');
            }

            return redirect('/')->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            \Log::error('Paystack callback error: '.$e->getMessage());
            return redirect('/')->with('error', 'An error occurred during registration.');
        }
    }

    /**
     * Display slip in browser (uses session('last_user_id'))
     */
    public function slip(Request $request)
    {
        $lastUserId = $request->session()->get('last_user_id');

        if (!$lastUserId) {
            return redirect('/')->with('error', 'No recent registration slip found.');
        }

        $user = User::find($lastUserId);
        $payment = Payment::where('user_id', $lastUserId)->orderBy('id','desc')->first();

        if (!$user || !$payment) {
            return redirect('/')->with('error', 'Slip not found.');
        }

        // optionally pass data to view
        return view('slip', compact('user','payment'));
    }
}
