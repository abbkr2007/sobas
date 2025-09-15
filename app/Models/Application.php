<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'application_id','surname','firstname','middlename','phone','email','dob','place_of_birth',
        'gender','state','lga','town','country','foreign_country','home_address','guardian',
        'guardian_address','guardian_phone','application_type','photo',
        'schools',
        'first_exam_type','first_exam_year','first_exam_number','first_center_number','first_subjects','first_grades',
        'second_exam_type','second_exam_year','second_exam_number','second_center_number','second_subjects','second_grades'
    ];

    protected $casts = [
        'schools' => 'array',
        'first_subjects' => 'array',
        'first_grades' => 'array',
        'second_subjects' => 'array',
        'second_grades' => 'array',
    ];
}
