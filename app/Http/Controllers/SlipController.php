<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class SlipController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $payment = Payment::where('user_id', $user->id)->latest()->first();

        return view('slip', compact('user', 'payment'));
    }
}
