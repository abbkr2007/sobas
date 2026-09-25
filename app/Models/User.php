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
        $serial = self::nextMatSerial($session ? $session->id : null, $prefix);

        return $prefix . str_pad($serial, 5, '0', STR_PAD_LEFT);
    }

    public static function nextMatSerial(?int $sessionId = null, ?string $prefix = null): int
    {
        $highestSerial = 0;

        $users = self::whereNotNull('mat_id');
        if ($sessionId !== null) {
            $users->where(function ($query) use ($sessionId, $prefix) {
                $query->where('academic_session_id', $sessionId);
                if ($prefix) {
                    $query->orWhere(function ($legacyQuery) use ($prefix) {
                        $legacyQuery->whereNull('academic_session_id')
                            ->where('mat_id', 'like', $prefix . '%');
                    });
                }
            });
        }

        $users->pluck('mat_id')->each(function ($matId) use (&$highestSerial) {
            if (preg_match('/^MAT\d{2}(\d+)$/i', (string) $matId, $matches)) {
                $highestSerial = max($highestSerial, (int) $matches[1]);
            }
        });

        return $highestSerial + 1;
    }
}
