<?php

namespace App\Http\Controllers\Auth;

use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Paystack;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register'); // Registration form view
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Charge 500 NGN
        $amountInKobo = 50000; // 500 NGN in Kobo

        // Store user data in session temporarily
        $request->session()->put('user_data', [
            'username' => strtolower($request->first_name) . strtolower($request->last_name),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => 'user',
            'amount' => $amountInKobo,
        ]);

        // Redirect to Paystack payment
        return redirect()->route('payment.redirectToGateway');
    }

    public function redirectToGateway(Request $request)
    {
        try {
            $userData = $request->session()->get('user_data');
            $amount = $userData['amount'];

            return Paystack::getAuthorizationUrl([
                'amount' => $amount,
                'email' => $userData['email'],
            ])->redirectNow();

        } catch (\Exception $e) {
            \Log::error('Paystack redirect error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment initiation failed. Please try again.');
        }
    }

    public function handleGatewayCallback(Request $request)
    {
        try {
            $paymentDetails = Paystack::getPaymentData();

            if ($paymentDetails['status'] == 'true' && $paymentDetails['data']['status'] == 'success') {

                $userData = $request->session()->get('user_data');
                if (!$userData) {
                    return redirect()->route('register')->with('error', 'Session expired. Please register again.');
                }

                // Create user
                $user = User::create([
                    'username' => $userData['username'],
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'phone_number' => $userData['phone_number'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'user_type' => $userData['user_type'],
                ]);

                // Send email
                Mail::to($user->email)->send(new UserRegisteredMail($user));

                // Log in user
                Auth::login($user);

                // Clear session
                $request->session()->forget('user_data');

                // Redirect to slip page
                return redirect()->route('slip.page')->with('success', 'Payment successful!');
            } else {
                \Log::error('Payment failed: ' . json_encode($paymentDetails));
                return redirect()->route('register')->with('error', 'Payment failed. Please try again.');
            }

        } catch (\Exception $e) {
            \Log::error('Paystack callback error: ' . $e->getMessage());
            return redirect()->route('register')->with('error', 'An error occurred during payment. Please try again.');
        }
    }
}
