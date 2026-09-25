<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;            // <-- add this
use Illuminate\Support\Str;     // <-- add this
use Illuminate\Support\Facades\Hash; // <-- and this
use App\Models\AcademicSession;
use App\Services\AcademicSessionService;

class BulkUserController extends Controller
{
    // Show the form to create bulk users
    public function showBulkForm()
    {
        return view('users.create', [
            'sessions' => AcademicSession::orderByDesc('start_year')->get(),
            'activeSession' => app(AcademicSessionService::class)->current(),
        ]);
    }

    public function createSession(Request $request, AcademicSessionService $sessions)
    {
        $data = $request->validate([
            'start_year' => 'required|integer|min:2000|max:2100|unique:academic_sessions,start_year',
        ]);

        $sessions->create((int) $data['start_year']);

        return back()->with('success', 'Academic session ' . $data['start_year'] . '/' . ($data['start_year'] + 1) . ' created and selected.');
    }

    // Handle bulk user creation
    public function Create(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:1000',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);

        $session = AcademicSession::findOrFail($request->academic_session_id);
        $year = substr((string) $session->start_year, -2);
        $prefix = 'MAT' . $year;

        for ($i = 0; $i < $request->count; $i++) {
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
                'academic_session_id' => $session->id,
            ]);
        }

        return back()->with('success', $request->count.' users created successfully.');
    }
}
