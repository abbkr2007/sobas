<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'fname', 
        'oname',
        'phone_number',
        'email',
        'password'
    ];
}
