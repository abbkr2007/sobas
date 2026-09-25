<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\DB;

class ConfirmationNumber
{
    public function forApplication(Application $application): ?string
    {
        return DB::transaction(function () use ($application) {
            // Serialize requests for the same applicant, including repeat downloads.
            $record = Application::whereKey($application->getKey())->lockForUpdate()->firstOrFail();
            if ($record->confirmation_number) {
                return $record->confirmation_number;
            }

            if ($record->status !== 'Confirmed') {
                throw new \LogicException('Only confirmed applicants can receive a confirmation number.');
            }

            $programme = strtolower(trim(str_replace('_', ' ', $record->application_type)));
            if ($programme === 'matric science') {
                $code = '10';
                $width = 4;
            } elseif ($programme === 'remedial' || strpos($programme, 'remedial ') === 0) {
                $code = '26';
                $width = 3;
            } else {
                // Other programmes retain their current letter numbering.
                return null;
            }

            $year = now()->year;
            DB::table('confirmation_sequences')->insertOrIgnore([
                'year' => $year, 'programme_code' => $code, 'last_serial' => 0,
            ]);
            $sequence = DB::table('confirmation_sequences')
                ->where('year', $year)->where('programme_code', $code);
            $serial = $sequence->lockForUpdate()->first()->last_serial + 1;
            $sequence->update(['last_serial' => $serial]);

            $number = substr((string) $year, -2) . $code . str_pad($serial, $width, '0', STR_PAD_LEFT);
            $record->confirmation_number = $number;
            $record->save();

            return $number;
        }, 5);
    }
}
