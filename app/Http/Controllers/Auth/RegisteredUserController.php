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
    // Show registration form on homepage
    public function create()
    {
        return view('auth.register');
    }

    // Store user info in session before payment
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

    // Redirect to Paystack
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
        // Get payment data from Paystack
        $paymentDetails = Paystack::getPaymentData();
        \Log::info('Paystack callback fired', ['paymentDetails' => $paymentDetails]);

        // Retrieve user data from session
        $userData = $request->session()->get('user_data');
        \Log::info('Session user_data', ['userData' => $userData]);

        if (!$userData) {
            return redirect('/')->with('error', 'Session expired or user data missing.');
        }

        // Check if payment was successful
        if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {

            // Generate a random password
            $randomPassword = \Illuminate\Support\Str::random(10);

            // Create user
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name'  => $userData['last_name'],
                'phone_number' => $userData['phone_number'],
                'email'      => $userData['email'],
                'password'   => Hash::make($randomPassword),
                'user_type'  => 'user',
            ]);

            if (!$user) {
                \Log::error('User creation failed.');
                return redirect('/')->with('error', 'Failed to create user.');
            }

            // Save payment record
            $payment = Payment::create([
                'user_id'       => $user->id,
                'reference'     => $paymentDetails['data']['reference'],
                'transaction_id'=> $paymentDetails['data']['id'],
                'amount'        => $paymentDetails['data']['amount'],
                'currency'      => $paymentDetails['data']['currency'],
                'status'        => $paymentDetails['data']['status'],
            ]);

            if (!$payment) {
                \Log::error('Payment creation failed.');
                return redirect('/')->with('error', 'Failed to save payment.');
            }

            // Optional: send email
            Mail::to($user->email)->send(new UserRegisteredMail($user));

            // Clear session data
            $request->session()->forget('user_data');
            $request->session()->put('last_user_id', $user->id);

            // Redirect to slip page
            return redirect()->route('slip')->with('success', 'Payment successful!');

        } else {
            \Log::error('Payment failed', ['paymentDetails' => $paymentDetails]);
            return redirect('/')->with('error', 'Payment failed. Please try again.');
        }

    } catch (\Exception $e) {
        \Log::error('Exception in Paystack callback', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);
        return redirect('/')->with('error', 'An error occurred during registration.');
    }
}
}