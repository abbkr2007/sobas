<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class RegistrationSettingsController extends Controller
{
    /**
     * Show registration settings
     */
    public function index()
    {
        $registrationOpen = Setting::getSetting('registration_open', true);
        $closedMessage = Setting::getSetting('registration_closed_message', 'Registration portal is currently closed.');

        return view('admin.registration-settings', [
            'registrationOpen' => $registrationOpen,
            'closedMessage' => $closedMessage,
        ]);
    }

    /**
     * Toggle registration status
     */
    public function toggle(Request $request)
    {
        $currentStatus = Setting::getSetting('registration_open', true);
        $newStatus = !$currentStatus;

        Setting::setSetting('registration_open', $newStatus ? '1' : '0', 'boolean', 'Whether registration portal is open for new applicants');

        $status = $newStatus ? 'OPEN' : 'CLOSED';

        return back()->with('success', "Registration portal is now {$status}.");
    }

    /**
     * Update registration settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'registration_open' => 'required|boolean',
            'registration_closed_message' => 'required|string|max:500',
        ]);

        Setting::setSetting('registration_open', $request->registration_open ? '1' : '0', 'boolean');
        Setting::setSetting('registration_closed_message', $request->registration_closed_message, 'string');

        return back()->with('success', 'Registration settings updated successfully.');
    }
}
