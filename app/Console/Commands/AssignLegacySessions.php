<?php

namespace App\Console\Commands;

use App\Models\AcademicSession;
use App\Services\LegacySessionAssignment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignLegacySessions extends Command
{
    protected $signature = 'sessions:assign-legacy {--apply : Fill unassigned session IDs after recording an audit file}';
    protected $description = 'Review or assign legacy users/applications to existing academic sessions';

    public function handle(LegacySessionAssignment $assignment): int
    {
        $this->table(['Session', 'Registration active'], AcademicSession::orderBy('start_year')->get()
            ->map(fn ($session) => [$session->label, $session->is_active ? 'Yes' : 'No'])->all());

        $plan = DB::transaction(function () use ($assignment) {
            $plan = $assignment->plan((bool) $this->option('apply'));
            if ($this->option('apply')) {
                $path = 'session-audits/' . now()->format('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.json';
                if (!Storage::disk('local')->put($path, json_encode($plan, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR))) {
                    throw new \RuntimeException('Could not save session assignment audit; no records changed.');
                }
                $assignment->apply($plan);
                $this->info('Audit: storage/app/' . $path);
            }

            return $plan;
        });

        $this->info(($this->option('apply') ? 'Assigned: ' : 'Ready to assign: ') . count($plan['changes']));
        $this->info('Unresolved records left unchanged: ' . count($plan['unresolved']));

        return 0;
    }
}
