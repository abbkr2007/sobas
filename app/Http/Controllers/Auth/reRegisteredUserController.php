<?php
namespace App\Http\Controllers\Auth;

use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Paystack;
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
        $amountInKobo = "500000"; // 500 NGN in Kobo

        // Store the request data temporarily in the session
        $request->session()->put('user_data', [
            'username' => strtolower($request->first_name) . strtolower($request->last_name),
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
        return view('auth.register'); // Ensure this view file exists
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
    // try {
    //     // Retrieve payment details from Paystack
    //     $paymentDetails = Paystack::getPaymentData();
    
        // Dump and halt the script
     //if isset($paymentDetails['status']=='true' && ($paymentDetails['data']['status']==success)
    //  dd($paymentDetails['data']['status']);
    // } catch (Exception $e) {
    //     // Handle exceptions
    //     dd($e->getMessage());
    // }
    
    try {
        // Retrieve payment details from Paystack
        $paymentDetails = Paystack::getPaymentData();
        // Log the complete payment details for debugging
        \Log::info('Paystack payment response: ', (array) $paymentDetails);

        // Check if payment was successful
        if ($paymentDetails['status']=='true' &&  $paymentDetails['data']['status']=='success') 
        
        {
            // Existing code..
            
            return redirect()->route('register')->with('success', 'Payment suceessfully.');



        } else {
            // Log a specific message for failure
            \Log::error('Payment failed or status not success: ' . json_encode($paymentDetails));
            return redirect()->route('register')->with('error', 'Payment failed. Please try again.');
        }
    } catch (\Exception $e) {
        // Log the exception for debugging
        \Log::error('Paystack callback error: ' . $e->getMessage());

        // Additional logging for the stack trace
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->route('register')->with('error', 'An error occurred during payment. Please try again.');
    }
}

}



