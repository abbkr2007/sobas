<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    protected $fillable = ['start_year', 'end_year', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getLabelAttribute(): string
    {
        return $this->start_year . '/' . $this->end_year;
    }
}