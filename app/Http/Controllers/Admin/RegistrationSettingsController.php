<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AcademicSession;
use App\Services\AcademicSessionService;
use Illuminate\Http\Request;

class RegistrationSettingsController extends Controller
{
    /**
     * Show registration settings
     */
    public function index()
    {
        $this->authorizeAdmin();
        $registrationOpen = Setting::getSetting('registration_open', true);
        $closedMessage = Setting::getSetting('registration_closed_message', 'Application portal is currently closed. Please try again later.');
        $sessions = AcademicSession::orderByDesc('start_year')->get();
        $activeSession = app(AcademicSessionService::class)->current();
        $applicationFee = Setting::getSetting('application_fee', config('paystack.application_fee'));
        $administrationFee = Setting::getSetting('administration_fee', config('paystack.administration_fee'));
        $confirmationFee = Setting::getSetting('confirmation_fee', 1000000);

        return view('admin.registration-settings', [
            'registrationOpen' => $registrationOpen,
            'closedMessage' => $closedMessage,
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'applicationFee' => $applicationFee,
            'administrationFee' => $administrationFee,
            'confirmationFee' => $confirmationFee,
        ]);
    }

    /**
     * Toggle registration status
     */
    public function toggle(Request $request)
    {
        $this->authorizeAdmin();
        $currentStatus = Setting::getSetting('registration_open', true);
        $newStatus = !$currentStatus;

        Setting::setSetting('registration_open', $newStatus ? '1' : '0', 'boolean', 'Whether application portal is open for new applicants');

        $status = $newStatus ? 'OPEN' : 'CLOSED';

        return back()->with('success', "Application portal is now {$status}.");
    }

    /**
     * Update registration settings
     */
    public function update(Request $request)
    {
        $this->authorizeAdmin();
        $request->validate([
            'registration_open' => 'required|boolean',
            'registration_closed_message' => 'required|string|max:500',
            'application_fee_naira' => 'required|numeric|min:0.01|max:1000000',
            'administration_fee_naira' => 'required|numeric|min:0.01|max:1000000',
            'confirmation_fee_naira' => 'required|numeric|min:0.01|max:1000000',
            'academic_session_id' => 'nullable|exists:academic_sessions,id',
            'new_session_start_year' => 'nullable|integer|min:2000|max:2100|unique:academic_sessions,start_year',
        ]);

        Setting::setSetting('registration_open', $request->registration_open ? '1' : '0', 'boolean');
        Setting::setSetting('registration_closed_message', $request->registration_closed_message, 'string');
        Setting::setSetting('application_fee', (int) round($request->application_fee_naira * 100), 'integer');
        Setting::setSetting('administration_fee', (int) round($request->administration_fee_naira * 100), 'integer');
        Setting::setSetting('confirmation_fee', (int) round($request->confirmation_fee_naira * 100), 'integer');

        if ($request->filled('new_session_start_year')) {
            $activeSession = app(AcademicSessionService::class)->create((int) $request->new_session_start_year);
        } elseif ($request->filled('academic_session_id')) {
            AcademicSession::query()->update(['is_active' => false]);
            AcademicSession::whereKey($request->academic_session_id)->update(['is_active' => true]);
            $activeSession = AcademicSession::find($request->academic_session_id);
        }

        return back()->with('success', 'Application settings updated successfully.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->user_type === 'admin', 403);
    }
}
