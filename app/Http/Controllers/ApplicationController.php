<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationSubmitted;

class ApplicationController extends Controller
{
    public function create()
    {
        return view('application-form'); // your blade form
    }

    public function store(Request $request)
    {
        // allowed grades and subjects lists (optional stricter validation)
        $grades = ['A1','B2','B3','C4','C5','C6','D7','E8','F9'];

        $request->validate([
            'application_id' => 'nullable|string',
            'surname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'dob' => ['required','date_format:d/m/Y'],
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'state' => 'required|string|max:255',
            'lga' => 'required|string|max:255',
            'town' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'foreign_country' => 'nullable|string|max:255',
            'home_address' => 'nullable|string|max:1000',
            'guardian' => 'nullable|string|max:255',
            'guardian_address' => 'nullable|string|max:1000',
            'guardian_phone' => 'nullable|string|max:50',
            'application_type' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'school_name' => 'nullable|array',
            'school_name.*' => 'nullable|string|max:255',
            'school_from' => 'nullable|array',
            'school_from.*' => 'nullable|string|max:10',
            'school_to' => 'nullable|array',
            'school_to.*' => 'nullable|string|max:10',

            // First sitting: exactly 9 entries required
            'first_subject' => 'required|array|size:9',
            'first_subject.*' => 'required|string|max:255',
            'first_grade' => 'required|array|size:9',
            'first_grade.*' => 'required|string|in:'.implode(',', $grades),

            // Second sitting optional (if provided must be parallel arrays)
            'second_subject' => 'nullable|array',
            'second_subject.*' => 'nullable|string|max:255',
            'second_grade' => 'nullable|array',
            'second_grade.*' => 'nullable|string|in:'.implode(',', $grades),

            'first_exam_type' => 'nullable|string|max:50',
            'first_exam_year' => 'nullable|integer',
            'first_exam_number' => 'nullable|string|max:50',
            'first_center_number' => 'nullable|string|max:50',

            'second_exam_type' => 'nullable|string|max:50',
            'second_exam_year' => 'nullable|integer',
            'second_exam_number' => 'nullable|string|max:50',
            'second_center_number' => 'nullable|string|max:50',

            'jamb_no' => 'nullable|string|max:100',
            'jamb_score' => 'nullable|string|max:100',
        ]);

        // handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('applications/photos', 'public');
        }

        // schools attended (pack into array of objects)
        $schools = [];
        $schoolNames = $request->input('school_name', []);
        foreach ($schoolNames as $i => $name) {
            if ($name) {
                $schools[] = [
                    'name' => $name,
                    'from' => $request->input("school_from.$i"),
                    'to'   => $request->input("school_to.$i"),
                ];
            }
        }

        // first sitting: pair subject + grade (validation already enforced size:9)
        $firstSubjects = $request->input('first_subject', []);
        $firstGrades = $request->input('first_grade', []);
        $firstResults = [];
        for ($i = 0; $i < count($firstSubjects); $i++) {
            $firstResults[] = [
                'subject' => $firstSubjects[$i],
                'grade' => $firstGrades[$i] ?? null,
            ];
        }

        // second sitting if any
        $secondResults = [];
        $secondSubjects = $request->input('second_subject', []);
        $secondGrades = $request->input('second_grade', []);
        for ($i = 0; $i < count($secondSubjects); $i++) {
            if (!empty($secondSubjects[$i])) {
                $secondResults[] = [
                    'subject' => $secondSubjects[$i],
                    'grade' => $secondGrades[$i] ?? null,
                ];
            }
        }

        // prepare exam meta
        $firstMeta = [
            'exam_type' => $request->first_exam_type,
            'exam_year' => $request->first_exam_year,
            'exam_number' => $request->first_exam_number,
            'center_number' => $request->first_center_number,
            'results' => $firstResults,
        ];

        $secondMeta = [
            'exam_type' => $request->second_exam_type,
            'exam_year' => $request->second_exam_year,
            'exam_number' => $request->second_exam_number,
            'center_number' => $request->second_center_number,
            'results' => $secondResults,
        ];

        // save application
        $application = Application::create([
            'application_id' => $request->application_id ?? null,
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
            'foreign_country' => $request->foreign_country,
            'home_address' => $request->home_address,
            'guardian' => $request->guardian,
            'guardian_address' => $request->guardian_address,
            'guardian_phone' => $request->guardian_phone,
            'application_type' => $request->application_type,
            'photo' => $photoPath,
            'schools' => $schools ?: null,
            'first_sitting' => $firstMeta,
            'second_sitting' => $secondMeta ?: null,
            'jamb_no' => $request->jamb_no,
            'jamb_score' => $request->jamb_score,
        ]);

        // send confirmation email to applicant (sync). For production consider queueing.
        try {
            Mail::to($application->email)->send(new ApplicationSubmitted($application));
        } catch (\Exception $e) {
            // don't break saving on mail failure; you can log it
            \Log::error('Mail sending failed: '.$e->getMessage());
        }

        return redirect()->route('application.preview', $application->id)
                         ->with('success', 'Application submitted successfully.');
    }

    public function preview($id)
    {
        $application = Application::findOrFail($id);
        return view('application.preview', compact('application'));
    }
}
