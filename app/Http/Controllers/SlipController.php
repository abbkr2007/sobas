<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;

class SlipController extends Controller
{
    public function index(Request $request)
    {
        // Try multiple methods to find the user
        $user = null;
        $token = $request->get('token');
        
        if ($token) {
            // Method 1: Token-based access (most reliable)
            $user = User::where('slip_token', $token)
                       ->where('slip_token_expires', '>', now())
                       ->first();
        }
        
        if (!$user) {
            // Method 2: Session-based access (fallback)
            $userId = session('last_user_id');
            if ($userId) {
                $user = User::find($userId);
            }
        }

        if (!$user) {
            return redirect('/')->with('error', 'Payment slip not found or expired. Please register again.');
        }

        $payment = Payment::where('user_id', $user->id)->latest()->first();

        if (!$payment) {
            return redirect('/')->with('error', 'Payment information not found. Please contact support.');
        }

        return view('auth.slip', compact('user', 'payment'));
    }
}
