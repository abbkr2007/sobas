<?php

namespace App\Services;

use App\Models\AcademicSession;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class AcademicSessionService
{
    public function create(int $startYear, bool $activate = true): AcademicSession
    {
        return DB::transaction(function () use ($startYear, $activate) {
            if ($activate) {
                AcademicSession::query()->update(['is_active' => false]);
            }

            return AcademicSession::create([
                'start_year' => $startYear,
                'end_year' => $startYear + 1,
                'is_active' => $activate,
            ]);
        });
    }

    public function current(): ?AcademicSession
    {
        return AcademicSession::where('is_active', true)->latest('start_year')->first();
    }

    public function viewing(): ?AcademicSession
    {
        $id = Setting::getSetting('viewing_academic_session_id');

        return $id ? AcademicSession::find($id) : $this->current();
    }

    public function scope($query, string $column = 'academic_session_id')
    {
        $session = $this->viewing();

        return $session ? $query->where($column, $session->id) : $query->whereRaw('1 = 0');
    }
}
