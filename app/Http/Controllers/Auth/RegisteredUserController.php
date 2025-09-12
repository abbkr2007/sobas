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

        $amountInKobo = 630000; // ₦6,300

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

            if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {

                // Generate MAT ID
                $year = date('y'); // last 2 digits of year
                $prefix = 'MAT'.$year;
                $lastUser = User::where('mat_id', 'like', $prefix.'%')
                                ->orderBy('id', 'desc')
                                ->first();
                $number = $lastUser ? (int) substr($lastUser->mat_id, 5) + 1 : 1;
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
            \Log::error('Paystack callback error: '.$e->getMessage());
            return redirect('/')->with('error', 'An error occurred during registration.');
        }
    }
}
