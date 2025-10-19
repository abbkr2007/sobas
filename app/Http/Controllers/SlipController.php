<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;

class SlipController extends Controller
{
    public function index(Request $request)
    {
        // Try to get user ID from session first, then from URL parameter
        $userId = session('last_user_id') ?? $request->get('user_id');

        if (!$userId) {
            return redirect('/')->with('error', 'No user found for slip. Please register again.');
        }

        $user = User::find($userId);
        $payment = Payment::where('user_id', $userId)->latest()->first();

        if (!$user || !$payment) {
            return redirect('/')->with('error', 'Invalid user or payment data. Please register again.');
        }

        return view('auth.slip', compact('user', 'payment'));
    }
}
