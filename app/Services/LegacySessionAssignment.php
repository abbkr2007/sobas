<?php

namespace App\Services;

use App\Models\AcademicSession;
use Illuminate\Support\Facades\DB;

class LegacySessionAssignment
{
    // Explicit assignments and the MATYY prefix must agree before filling a gap.
    public function plan(bool $lock = false): array
    {
        $sessions = AcademicSession::all()->groupBy(fn ($session) => substr((string) $session->start_year, -2));
        $groups = [];
        foreach (['users' => 'mat_id', 'applications' => 'application_id'] as $table => $column) {
            $query = DB::table($table)->select(['id', $column, 'academic_session_id']);
            if ($lock) {
                $query->lockForUpdate();
            }
            foreach ($query->get() as $row) {
                $key = strtoupper(trim((string) $row->{$column}));
                $groups[$key][] = ['table' => $table, 'id' => $row->id, 'academic_session_id' => $row->academic_session_id];
            }
        }

        $changes = [];
        $unresolved = [];
        foreach ($groups as $matric => $records) {
            $ids = array_values(array_unique(array_filter(array_column($records, 'academic_session_id'))));
            $prefixSession = null;
            $hasPrefix = preg_match('/^MAT(\d{2})\d+$/', $matric, $matches);
            if ($hasPrefix && isset($sessions[$matches[1]]) && $sessions[$matches[1]]->count() === 1) {
                $prefixSession = $sessions[$matches[1]]->first()->id;
            }
            if ($prefixSession) {
                $ids = array_values(array_unique(array_merge($ids, [$prefixSession])));
            }
            $target = count($ids) === 1 ? $ids[0] : null;
            $ambiguous = !$matric || count($ids) > 1 || ($hasPrefix && !$prefixSession);
            foreach ($records as $record) {
                if ($ambiguous || (!$target && !$record['academic_session_id'])) {
                    $unresolved[] = $record + ['reason' => $ambiguous ? 'Conflicting or missing session evidence' : 'No reliable session evidence'];
                } elseif (!$record['academic_session_id']) {
                    $changes[] = $record + ['new_session_id' => $target];
                }
            }
        }

        return compact('changes', 'unresolved');
    }

    public function apply(array $plan): void
    {
        foreach ($plan['changes'] as $change) {
            DB::table($change['table'])->where('id', $change['id'])
                ->whereNull('academic_session_id')
                ->update(['academic_session_id' => $change['new_session_id']]);
        }
    }
}
