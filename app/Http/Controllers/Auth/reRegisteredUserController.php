<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Payment;
use Paystack;

class RegisteredUserController extends Controller
{
    // Show registration form
    public function create() {
        return view('auth.register'); // Your homepage form
    }

    // Store user data in session and redirect to Paystack
    public function store(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string|max:20',
        ]);

        $amountInKobo = 630000; // ₦6,300

        $request->session()->put('user_data', [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'amount' => $amountInKobo,
        ]);

        return redirect()->route('payment.redirectToGateway');
    }

    // Redirect to Paystack
    public function redirectToGateway(Request $request) {
        $userData = $request->session()->get('user_data');

        return Paystack::getAuthorizationUrl([
            'email' => $userData['email'],
            'amount' => $userData['amount'],
        ])->redirectNow();
    }

    // Handle Paystack callback
    public function handleGatewayCallback(Request $request) {
        $paymentDetails = Paystack::getPaymentData();

        \Log::info('Paystack callback', ['data' => $paymentDetails]);

        if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {

            $userData = $request->session()->get('user_data');

            // Generate a random password
            $randomPassword = Str::random(10);

            // Create user
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'phone_number' => $userData['phone_number'],
                'email' => $userData['email'],
                'password' => Hash::make($randomPassword),
                'user_type' => 'user',
            ]);

            // Log the user in
            Auth::login($user);

            // Save payment
            Payment::create([
                'user_id' => $user->id,
                'transaction_id' => $paymentDetails['data']['id'],
                'reference' => $paymentDetails['data']['reference'],
                'amount' => $paymentDetails['data']['amount'],
                'currency' => $paymentDetails['data']['currency'],
                'status' => $paymentDetails['data']['status'],
            ]);

            // Clear session
            $request->session()->forget('user_data');

            // Store user ID for slip page
            $request->session()->put('last_user_id', $user->id);

            return redirect()->route('slip')->with('success', 'Payment successful!');
        }

        return redirect('/')->with('error', 'Payment failed, please try again.');
    }
}
