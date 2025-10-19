<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'user_type',
        'mat_id',          // Add matric number
        'plain_password',  // Optional: to display on slip/email
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'plain_password', // hide sensitive field
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    // Full name accessor
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Relationship with profile (optional)
    public function userProfile() {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    // Relationship with payments
    public function payments() {
        return $this->hasMany(Payment::class);
    }

    // Helper function to generate MAT ID (example: MAT2400001)
    public static function generateMatId() {
        $year = date('y'); // last 2 digits of current year
        $lastUser = self::latest('id')->first();
        $serial = $lastUser ? str_pad($lastUser->id + 1, 5, '0', STR_PAD_LEFT) : '00001';
        return 'MAT' . $year . $serial;
    }
}
