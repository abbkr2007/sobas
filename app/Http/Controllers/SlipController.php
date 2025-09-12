<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;

class SlipController extends Controller
{
    public function index()
    {
        $userId = session('last_user_id');

        if (!$userId) {
            return redirect('/')->with('error', 'No user found for slip.');
        }

        $user = User::find($userId);
        $payment = Payment::where('user_id', $userId)->latest()->first();

        return view('slip', compact('user', 'payment'));
    }
}
