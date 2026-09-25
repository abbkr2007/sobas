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
        'academic_session_id',
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

    public function academicSession() {
        return $this->belongsTo(AcademicSession::class);
    }

    // Relationship with payments
    public function payments() {
        return $this->hasMany(Payment::class);
    }

    // Helper function to generate a matric number for the active session.
    public static function generateMatId() {
        $session = AcademicSession::where('is_active', true)->latest('start_year')->first();
        $year = $session ? substr((string) $session->start_year, -2) : now()->format('y');
        $prefix = 'MAT' . $year;
        $lastUser = self::where('mat_id', 'like', $prefix . '%')->orderByDesc('id')->first();
        $serial = $lastUser ? (int) substr($lastUser->mat_id, 5) + 1 : 1;

        return $prefix . str_pad($serial, 5, '0', STR_PAD_LEFT);
    }
}
