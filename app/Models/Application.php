<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'surname', 'firstname', 'middlename', 'phone', 'email',
        'dob', 'place_of_birth', 'gender', 'state', 'lga', 'town', 'country',
        'home_address', 'guardian', 'guardian_address', 'guardian_phone',
        'application_type', 'qualification', 'institution', 'graduation_year',
        'photo', 'resume', 'supporting_document'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
