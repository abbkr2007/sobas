<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\Application;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'application_id'=>'required',
            'surname'=>'required','firstname'=>'required','middlename'=>'nullable',
            'phone'=>'required','email'=>'required|email','dob'=>'required',
            'place_of_birth'=>'nullable','gender'=>'required','state'=>'required','lga'=>'required',
            'town'=>'nullable','country'=>'required','foreign_country'=>'nullable',
            'home_address'=>'nullable','guardian'=>'nullable','guardian_address'=>'nullable',
            'guardian_phone'=>'nullable','application_type'=>'required',

            'school_name.*'=>'nullable',
            'school_from.*'=>'nullable',
            'school_to.*'=>'nullable',

            'first_exam_type'=>'nullable','first_exam_year'=>'nullable',
            'first_exam_number'=>'nullable','first_center_number'=>'nullable',
            'first_subject.*'=>'nullable','first_grade.*'=>'nullable',

            'second_exam_type'=>'nullable','second_exam_year'=>'nullable',
            'second_exam_number'=>'nullable','second_center_number'=>'nullable',
            'second_subject.*'=>'nullable','second_grade.*'=>'nullable',

            'photo'=>'nullable|image|max:2048',
        ]);

        // handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $extension = strtolower($photo->getClientOriginalExtension());

            $filename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);

            // If JPEG/JPG, convert to PNG
            if (in_array($extension, ['jpeg', 'jpg'])) {
                $image = Image::make($photo->getRealPath())->encode('png');
                $filename .= '.png';
                Storage::disk('public')->put('photos/' . $filename, (string) $image);
            } else {
                // For PNG or other formats, store as-is
                $filename .= '.' . $extension;
                $photo->storeAs('photos', $filename, 'public');
            }

            $data['photo'] = 'photos/' . $filename;
        }

        // combine schools
        $schools = [];
        if($request->school_name){
            foreach($request->school_name as $i=>$name){
                if($name){
                    $schools[] = [
                        'name'=>$name,
                        'from'=>$request->school_from[$i] ?? null,
                        'to'=>$request->school_to[$i] ?? null
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

        // Redirect to the acknowledgment slip view
        return redirect()->route('applications.show', $application->id)
                         ->with('success','Application submitted successfully!');
    }

    // New method to show acknowledgment slip
    public function show($id)
    {
        $application = Application::findOrFail($id);

        // Generate PDF and stream it in the browser
        $pdf = PDF::loadView('applications.acknowledgment', compact('application'));

        return $pdf->stream('Biodata_'.$application->application_id.'.pdf');
        $pdf->setOptions(['isRemoteEnabled' => true]);
        
    }
}
