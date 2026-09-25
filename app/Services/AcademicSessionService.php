<?php

namespace App\Services;

use App\Models\AcademicSession;
use Illuminate\Support\Facades\DB;

class AcademicSessionService
{
    public function create(int $startYear): AcademicSession
    {
        return DB::transaction(function () use ($startYear) {
            AcademicSession::query()->update(['is_active' => false]);

            return AcademicSession::create([
                'start_year' => $startYear,
                'end_year' => $startYear + 1,
                'is_active' => true,
            ]);
        });
    }

    public function current(): ?AcademicSession
    {
        return AcademicSession::where('is_active', true)->latest('start_year')->first();
    }
}