<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;            // <-- add this
use Illuminate\Support\Str;     // <-- add this
use Illuminate\Support\Facades\Hash; // <-- and this

class BulkUserController extends Controller
{
    // Show the form to create bulk users
    public function showBulkForm()
    {
        return view('users.create');
    }

    // Handle bulk user creation
    public function Create(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:1000',
        ]);

        for ($i = 0; $i < $request->count; $i++) {
            $year = date('y');
            $prefix = 'MAT' . $year;

            $lastUser = User::where('mat_id', 'like', $prefix.'%')
                            ->orderBy('id','desc')
                            ->first();

            $number = $lastUser ? (int)substr($lastUser->mat_id, 5) + 1 : 1;
            $matId = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);

            $plainPassword = Str::random(10);

            User::create([
                'first_name'     => 'User'.$number,
                'last_name'      => 'Example',
                'phone_number'   => '080000000'.$number,
                'email'          => 'user'.$number.'@example.com',
                'password'       => Hash::make($plainPassword),
                'plain_password' => $plainPassword,
                'user_type'      => 'user',
                'mat_id'         => $matId,
            ]);
        }

        return back()->with('success', $request->count.' users created successfully.');
    }
}
