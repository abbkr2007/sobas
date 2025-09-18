<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function store(Request $request)
    
    {
        $data = $request->validate([
            'application_id'        => 'required',
            'surname'                => 'required',
            'firstname'              => 'required',
            'middlename'              => 'nullable',
            'phone'                  => 'required',
            'email'                  => 'required|email',
            'dob'                    => 'required|date',
            'place_of_birth'         => 'nullable',
            'gender'                  => 'required',
            'state'                   => 'required',
            'lga'                     => 'required',
            'town'                    => 'nullable',
            'country'                 => 'required',
            'foreign_country'         => 'nullable',
            'home_address'             => 'nullable',
            'guardian'                 => 'nullable',
            'guardian_address'         => 'nullable',
            'guardian_phone'           => 'nullable',
            'application_type'         => 'required',

            'school_name.*'             => 'required',
            'school_from.*'              => 'required',
            'school_to.*'                => 'required',

            'first_exam_type'             => 'required',
            'first_exam_year'              => 'required',
            'first_exam_number'            => 'required',
            'first_center_number'          => 'required',
            'first_subject.*'               => 'required',
            'first_grade.*'                  => 'required',

            'second_exam_type'                => 'nullable',
            'second_exam_year'                 => 'nullable',
            'second_exam_number'               => 'nullable',
            'second_center_number'             => 'nullable',
            'second_subject.*'                  => 'nullable',
            'second_grade.*'                     => 'nullable',

            'photo' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $extension = strtolower($photo->getClientOriginalExtension());

            $uniqueName = uniqid('photo_') . '.' . (in_array($extension, ['jpeg','jpg']) ? 'png' : $extension);
            $destinationPath = public_path('images/photos');

            if (in_array($extension, ['jpeg','jpg'])) {
                $image = Image::make($photo->getRealPath())->encode('png');
                $image->save($destinationPath . '/' . $uniqueName);
            } else {
                $photo->move($destinationPath, $uniqueName);
            }

            $data['photo'] = 'images/photos/' . $uniqueName;
        }

        $schools = [];
        if ($request->school_name) {
            foreach ($request->school_name as $i => $name) {
                if ($name) {
                    $schools[] = [
                        'name' => $name,
                        'from' => $request->school_from[$i] ?? null,
                        'to' => $request->school_to[$i] ?? null
                    ];
                }
            }
        }
        $data['schools'] = $schools;

        $data['first_subjects'] = $request->first_subject ?? [];
        $data['first_grades'] = $request->first_grade ?? [];
        $data['second_subjects'] = $request->second_subject ?? [];
        $data['second_grades'] = $request->second_grade ?? [];

        $application = Application::create($data);

        // flash the id to session so we can open it in JS
        return redirect()->back()->with([
            'success' => 'application submited successfully',
            'application_id' => $application->id
        ]);
    }

    public function show($id)
    {
        $application = Application::findOrFail($id);
        return view('applications.acknowledgment', compact('application'));
    }
}
