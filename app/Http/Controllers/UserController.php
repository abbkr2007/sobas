<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id','mat_id','first_name','last_name','email','phone_number','plain_password']);

            return DataTables::of($users)
                ->addColumn('full_name', function ($row) {
                    return '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-column="first_name">'.e($row->first_name).'</span> '.
                           '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-column="last_name">'.e($row->last_name).'</span>';
                })
                ->addColumn('email', fn($row) => '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-column="email">'.e($row->email).'</span>')
                ->addColumn('phone_number', fn($row) => '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-column="phone_number">'.e($row->phone_number).'</span>')
                ->addColumn('plain_password', fn($row) => '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-column="plain_password">'.e($row->plain_password).'</span>')
                ->rawColumns(['full_name','email','phone_number','plain_password'])
                ->make(true);
        }

        return view('users.index');
    }
public function inlineUpdate(Request $request)
{
    try {
        $user = User::findOrFail($request->id);

        // Allowed fields to update
        $allowed = ['mat_id','first_name','last_name','email','phone_number','plain_password'];

        if (!in_array($request->column, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid column name'
            ], 400);
        }

        // If empty, store null
        $value = $request->value === '' ? null : $request->value;

        $user->{$request->column} = $value;

        // If updating plain_password, also update hashed password
        if ($request->column === 'plain_password' && $value !== null) {
            $user->password = Hash::make($value);
        }

        $user->save();

        return response()->json(['success' => true]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
