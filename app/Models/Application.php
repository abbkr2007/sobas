<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'application_id','surname','firstname','middlename','phone','email','dob','place_of_birth',
        'gender','state','lga','town','country','foreign_country','home_address','guardian',
        'guardian_address','guardian_phone','application_type','photo','schools','first_sitting','second_sitting',
        'jamb_no','jamb_score'
    ];

    protected $casts = [
        'schools' => 'array',
        'first_sitting' => 'array',
        'second_sitting' => 'array',
    ];
}
