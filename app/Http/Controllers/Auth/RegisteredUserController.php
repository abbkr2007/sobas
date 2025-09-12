<?php
namespace App\Http\Controllers\Auth;

use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Payment;
use App\Providers\RouteServiceProvider;
use Paystack;
use App\Models\Organization;
use App\Events\Registered;
use App\Notifications\UserRegistered;
use Illuminate\Support\Facades\Notification;


class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        // Assuming you want to charge 500 NGN
        $amountInKobo = "630000"; // 500 NGN in Kobo

        // Store the request data temporarily in the session
        $request->session()->put('user_data', [
            
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,  // Do not hash the password yet
            'user_type' => 'user',
            'amount' => $amountInKobo // Store the amount
        ]);

        // Redirect to payment gateway
        return redirect()->route('payment.redirectToGateway');
    }

    public function create()
    {
        $organizations = Organization::all();

        // Check what data is being fetched
        // if ($organizations->isEmpty()) {
        //     \Log::info('No organizations found in the database.');
        // } else {
        //     \Log::info('Organizations found:', $organizations->toArray());
        // }

        return view('auth.register', compact('organizations'));
    }

    public function redirectToGateway(Request $request)
    {
        try {
            // Retrieve the amount from the session
            $userData = $request->session()->get('user_data');
            $amount = $userData['amount']; // Amount in kobo

            \Log::info('Amount sent to Paystack: ' . $amount);

            // Get authorization URL
            return Paystack::getAuthorizationUrl([
                'amount' => $amount, // Amount in kobo
                'email' => $userData['email'], // Required email field
            ])->redirectNow();
        } catch (\Exception $e) {
            \Log::error('Paystack redirect error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'The Paystack token has expired. Please refresh the page and try again.');
        }
    }


public function handleGatewayCallback(Request $request)
{
    try {
        $paymentDetails = Paystack::getPaymentData();

        \Log::info('Payment details: ' . json_encode($paymentDetails));

        // Check if payment was successful
        if ($paymentDetails['status'] == 'true' && $paymentDetails['data']['status'] == 'success') {
            // Retrieve user data from session
            $userData = $request->session()->get('user_data');

            // Capture the plain password
            $plainPassword = $userData['password'];
            // dd($plainPassword);
           // Create the user and log them in
            $user = User::create([

                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'phone_number' => $userData['phone_number'],
                'password' => Hash::make($userData['password']),
                'plain_password' => $plainPassword,
                'user_type' => $userData['user_type'],
            ]);
            // Save payment data to the database
            $payment = new Payment();
            $payment->reference = $paymentDetails['data']['reference'];
            $payment->user_id = $user->id;
            $payment->transaction_id = $paymentDetails['data']['id'];
            $payment->amount = $paymentDetails['data']['amount'];
            $payment->currency = $paymentDetails['data']['currency'];
            $payment->status = $paymentDetails['data']['status'];
            $payment->save();

            // Send registration email
            // Mail::to($user->email)->send(new UserRegisteredMail($user));

            Mail::to($user->email)->send(new UserRegisteredMail($user));
            $user->plain_password = null;
            $user->save();
            // Clear the session data
            $request->session()->forget('user_data');

            return redirect()->route('slip')->with('success', 'Payment successful and Kindly check your mailbox for more informations.');
        } else {
            \Log::error('Payment failed or status not success: ' . json_encode($paymentDetails));
            return redirect()->route('slip')->with('error', 'Payment failed. Please try again.');
        }
    } catch (\Exception $e) {
        \Log::error('Paystack callback error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return redirect()->route('slip')->with('error', 'An error occurred during Registration.');
    }
}


}



