<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class  BulkUserController extends Controller
{
    // existing methods like register, handleGatewayCallback ...

    public function bulkCreate(Request $request)
    {
        // Only allow admins
        if (auth()->user()->user_type !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $count = (int) $request->input('count', 1); // how many users admin wants

        for ($i = 0; $i < $count; $i++) {

            // Generate MAT ID
            $year = '25';
            $prefix = 'MAT' . $year;
            $lastUser = User::where('mat_id', 'like', $prefix . '%')
                            ->orderBy('id', 'desc')
                            ->first();
            $number = $lastUser ? (int) substr($lastUser->mat_id, 5) + 1 : 1;
            $matId = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);

            // Generate random user data
            $firstName = 'User' . Str::random(3);
            $lastName = 'Test' . Str::random(3);
            $phone = '080' . rand(10000000, 99999999);
            $email = strtolower($firstName . '.' . $lastName . $i) . '@example.com';

            // Generate random password
            $plainPassword = Str::random(10);

            // Create user
            User::create([
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'phone_number'   => $phone,
                'email'          => $email,
                'password'       => Hash::make($plainPassword),
                'plain_password' => $plainPassword,
                'user_type'      => 'user',
                'mat_id'         => $matId,
            ]);
        }

        return back()->with('success', "$count users created successfully!");
    }
}
