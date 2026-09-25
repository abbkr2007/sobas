<?php

namespace App\Services;

use App\Models\Application;
use RuntimeException;

class ConfirmedApplicantPhoto
{
    public function copy(Application $application, string $confirmationNumber): void
    {
        if (!$application->photo) {
            return;
        }

        $destinationDirectory = public_path('images/confirmation/photos');
        if (!is_dir($destinationDirectory) && !mkdir($destinationDirectory, 0755, true) && !is_dir($destinationDirectory)) {
            throw new RuntimeException('Unable to create the confirmation photo directory.');
        }

        $safeNumber = preg_replace('/[^A-Za-z0-9_-]/', '', $confirmationNumber);
        $extension = strtolower(pathinfo($application->photo, PATHINFO_EXTENSION)) ?: 'jpg';
        $destinationPath = $destinationDirectory . DIRECTORY_SEPARATOR . $safeNumber . '.' . $extension;
        $destinationRelativePath = 'images/confirmation/photos/' . $safeNumber . '.' . $extension;
        $sourcePath = public_path($application->photo);

        if (realpath($sourcePath) === realpath($destinationPath)) {
            return;
        }

        if (!file_exists($sourcePath)) {
            if (file_exists($destinationPath)) {
                $application->photo = $destinationRelativePath;
                $application->save();
                return;
            }

            throw new RuntimeException('The applicant photo could not be found.');
        }

        if (file_exists($destinationPath) && !unlink($destinationPath)) {
            throw new RuntimeException('Unable to replace the existing confirmation photo.');
        }

        if (!copy($sourcePath, $destinationPath)) {
            throw new RuntimeException('Unable to copy the applicant photo.');
        }

        $application->photo = $destinationRelativePath;
        $application->save();
    }
}