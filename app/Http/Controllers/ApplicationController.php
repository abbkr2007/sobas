<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    // Step 3a: Show the form or slip
    public function showForm()
    {
        $application = Application::where('user_id', Auth::id())->first();

        if ($application) {
            return view('application.slip', compact('application'));
        }

        return view('application.form');
    }

    // Step 3b: Handle form submission
    public function submitForm(Request $request)
    {
        $request->validate([
            'surname' => 'required|string',
            'firstname' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'dob' => 'required|string',
            'gender' => 'required|string',
            'state' => 'required|string',
            'lga' => 'required|string',
            'country' => 'required|string',
            'application_type' => 'required|string',
        ]);

        // Save files
        $photo = $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null;
        $resume = $request->file('resume') ? $request->file('resume')->store('resumes', 'public') : null;
        $supporting_document = $request->file('supporting_document') ? $request->file('supporting_document')->store('documents', 'public') : null;

        $application = Application::create([
            'user_id' => Auth::id(),
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'phone' => $request->phone,
            'email' => $request->email,
            'dob' => $request->dob,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'state' => $request->state,
            'lga' => $request->lga,
            'town' => $request->town,
            'country' => $request->country,
            'home_address' => $request->home_address,
            'guardian' => $request->guardian,
            'guardian_address' => $request->guardian_address,
            'guardian_phone' => $request->guardian_phone,
            'application_type' => $request->application_type,
            'qualification' => $request->qualification,
            'institution' => $request->institution,
            'graduation_year' => $request->graduation_year,
            'photo' => $photo,
            'resume' => $resume,
            'supporting_document' => $supporting_document
        ]);

        // Send email if needed
        // Mail::to($request->email)->send(new ApplicationSubmitted($application));

        return redirect()->route('application.slip')->with('success', 'Application submitted successfully!');
    }

    // Step 3c: Show slip page
    public function slip()
    {
        $application = Application::where('user_id', Auth::id())->firstOrFail();
        return view('application.slip', compact('application'));
    }
}
