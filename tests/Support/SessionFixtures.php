<?php

namespace Tests\Support;

use App\Models\AcademicSession;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait SessionFixtures
{
    protected function setUpSessionFixtures(int $startYear = 2026): void
    {
        if (!Schema::hasTable('academic_sessions')) {
            (require database_path('migrations/2026_09_25_000002_create_academic_sessions_table.php'))->up();
        }
        if (!Schema::hasTable('settings')) {
            (require database_path('migrations/2026_02_19_000001_create_settings_table.php'))->up();
        }
        $session = AcademicSession::firstOrCreate(['start_year' => $startYear], [
            'end_year' => $startYear + 1, 'is_active' => true,
        ]);
        foreach (['applications', 'users'] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'academic_session_id')) {
                Schema::table($table, function (Blueprint $blueprint) use ($session) {
                    $blueprint->unsignedBigInteger('academic_session_id')->nullable()->default($session->id);
                });
            }
        }
    }
}
