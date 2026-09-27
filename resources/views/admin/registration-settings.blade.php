<x-app-layout :assets="$assets ?? []">
    <div class="container-fluid px-3 px-md-4">
        <div class="application-settings-page">
            <div class="settings-header">
                <div>
                    <p class="settings-eyebrow mb-1">Admin Control</p>
                    <h4 class="settings-title mb-1">Application Control</h4>
                    <p class="settings-subtitle mb-0">Manage portal access, academic sessions, and applicant fees.</p>
                </div>
                <span class="settings-state {{ $registrationOpen ? 'is-open' : 'is-closed' }}">
                    <i class="fas {{ $registrationOpen ? 'fa-check-circle' : 'fa-lock' }} me-2"></i>
                    {{ $registrationOpen ? 'Open' : 'Closed' }}
                </span>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="settings-panel status-panel">
                        <div class="panel-icon {{ $registrationOpen ? 'panel-icon-open' : 'panel-icon-closed' }}">
                            <i class="fas {{ $registrationOpen ? 'fa-door-open' : 'fa-lock' }}"></i>
                        </div>
                        <div class="panel-content">
                            <p class="panel-label mb-1">Current Status</p>
                            <h5 class="panel-title mb-2">
                                Application portal is {{ $registrationOpen ? 'open' : 'closed' }}
                            </h5>
                            <p class="panel-text mb-0">
                                @if($registrationOpen)
                                    New applicants can access the form and submit applications.
                                @else
                                    New applicants will see the closed message. Existing users can still log in.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="settings-panel action-panel">
                        <div>
                            <p class="panel-label mb-1">Quick Toggle</p>
                            <h5 class="panel-title mb-2">
                                {{ $registrationOpen ? 'Close applications' : 'Open applications' }}
                            </h5>
                            <p class="panel-text mb-3">
                                Change the portal status with one click.
                            </p>
                        </div>
                        <form method="POST" action="{{ route('admin.registration.toggle') }}">
                            @csrf
                            <button type="submit" class="btn {{ $registrationOpen ? 'btn-danger' : 'btn-success' }} btn-lg w-100">
                                @if($registrationOpen)
                                    <i class="fas fa-lock me-2"></i>Close Portal
                                @else
                                    <i class="fas fa-unlock me-2"></i>Open Portal
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <nav class="settings-wizard-nav" aria-label="Application Control sections" role="tablist">
                <button type="button" class="wizard-step is-active" id="wizard-tab-1" role="tab" aria-selected="true" aria-controls="portal-access-step" data-step-target="1">
                    <span class="wizard-step-number">01</span><span>Portal Access</span>
                </button>
                <button type="button" class="wizard-step" id="wizard-tab-2" role="tab" aria-selected="false" aria-controls="academic-sessions-step" data-step-target="2">
                    <span class="wizard-step-number">02</span><span>Academic Sessions</span>
                </button>
                <button type="button" class="wizard-step" id="wizard-tab-3" role="tab" aria-selected="false" aria-controls="payment-fees-step" data-step-target="3">
                    <span class="wizard-step-number">03</span><span>Payment Fees</span>
                </button>
            </nav>

            <form method="POST" action="{{ route('admin.registration.update') }}" id="applicationControlForm" novalidate>
                @csrf

                <section class="settings-panel settings-section form-panel mb-4 wizard-pane" id="portal-access-step" role="tabpanel" aria-labelledby="wizard-tab-1" data-step-panel="1">
                    <div class="section-heading">
                        <span class="section-number">01</span>
                        <div>
                            <p class="panel-label mb-1">Portal Access</p>
                            <h5 class="panel-title mb-1" id="portal-access-heading" tabindex="-1">Application availability</h5>
                            <p class="panel-text mb-0">Choose whether new applicants can submit applications and set the message shown while the portal is closed.</p>
                        </div>
                    </div>
                    <input type="hidden" name="registration_open" value="0">
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-5">
                            <div class="access-switch h-100">
                                <div>
                                    <label class="form-label fw-bold mb-1" for="registrationOpen">Allow new applications</label>
                                    <p class="text-muted small mb-0">Turn this off to show the closed-page message.</p>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="registration_open" id="registrationOpen" value="1" {{ $registrationOpen ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <label class="form-label fw-bold" for="closedMessage">Closed-page message</label>
                            <textarea class="form-control" name="registration_closed_message" id="closedMessage" rows="3" required>{{ $closedMessage }}</textarea>
                            @error('registration_closed_message')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="wizard-actions wizard-actions-end">
                        <button type="button" class="btn btn-success wizard-next" data-next-step="2">Next <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </section>

                <section class="settings-panel settings-section form-panel mb-4 wizard-pane" id="academic-sessions-step" role="tabpanel" aria-labelledby="wizard-tab-2" data-step-panel="2">
                    <div class="section-heading">
                        <span class="section-number">02</span>
                        <div>
                            <p class="panel-label mb-1">Academic Sessions</p>
                            <h5 class="panel-title mb-1" id="academic-sessions-heading" tabindex="-1">Registration and admin view</h5>
                            <p class="panel-text mb-0">Set the session for new registrations separately from the session administrators are currently viewing.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label fw-bold" for="academicSession">Registration session</label>
                            <select class="form-select" name="academic_session_id" id="academicSession">
                                <option value="">Keep current session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ optional($activeSession)->id === $session->id ? 'selected' : '' }}>{{ $session->label }}{{ $session->is_active ? ' (Active)' : '' }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">New applicants are assigned to this session.</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-bold" for="newSessionYear">Create a session</label>
                            <input class="form-control" type="number" name="new_session_start_year" id="newSessionYear" min="2000" max="2100" placeholder="2026">
                            <div class="form-text">Creates a session without changing registration settings.</div>
                            @error('new_session_start_year')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold" for="viewingSession">Session to view and manage</label>
                            <select class="form-select" name="viewing_academic_session_id" id="viewingSession">
                                <option value="">Follow registration session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ (string) old('viewing_academic_session_id', $viewingSessionId) === (string) $session->id ? 'selected' : '' }}>{{ $session->label }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Controls dashboard totals, applicants, admissions, confirmations, users, payments, and exports for administrators.</div>
                            @error('viewing_academic_session_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="wizard-actions">
                        <button type="button" class="btn btn-light wizard-back" data-previous-step="1"><i class="fas fa-arrow-left me-2"></i>Back</button>
                        <button type="button" class="btn btn-success wizard-next" data-next-step="3">Next <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </section>

                <section class="settings-panel settings-section form-panel mb-4 wizard-pane" id="payment-fees-step" role="tabpanel" aria-labelledby="wizard-tab-3" data-step-panel="3">
                    <div class="section-heading">
                        <span class="section-number">03</span>
                        <div>
                            <p class="panel-label mb-1">Payment Fees</p>
                            <h5 class="panel-title mb-1" id="payment-fees-heading" tabindex="-1">Applicant charges</h5>
                            <p class="panel-text mb-0">Set the amounts used at checkout. Values are entered in naira.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold" for="applicationFee">Application fee (NGN)</label>
                            <input class="form-control" type="number" name="application_fee_naira" id="applicationFee" min="0.01" max="1000000" step="0.01" value="{{ number_format($applicationFee / 100, 2, '.', '') }}" required>
                            @error('application_fee_naira')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" for="administrationFee">Administration fee (NGN)</label>
                            <input class="form-control" type="number" name="administration_fee_naira" id="administrationFee" min="0.01" max="1000000" step="0.01" value="{{ number_format($administrationFee / 100, 2, '.', '') }}" required>
                            @error('administration_fee_naira')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" for="confirmationFee">Confirmation fee (NGN)</label>
                            <input class="form-control" type="number" name="confirmation_fee_naira" id="confirmationFee" min="0.01" max="1000000" step="0.01" value="{{ number_format($confirmationFee / 100, 2, '.', '') }}" required>
                            <div class="form-text">Charged after admission. Default: ₦10,000.</div>
                            @error('confirmation_fee_naira')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="wizard-actions">
                        <button type="button" class="btn btn-light wizard-back" data-previous-step="2"><i class="fas fa-arrow-left me-2"></i>Back</button>
                        <div class="settings-actions">
                            <a href="{{ route('dashboard') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save me-2"></i>Save Changes</button>
                        </div>
                    </div>
                </section>
            </form>

        </div>
    </div>

    <style>
        .application-settings-page {
            max-width: 1180px;
            margin: 0 auto;
            padding: 18px 0 28px;
        }

        .settings-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            padding: 22px 24px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #e8edf3;
            box-shadow: 0 8px 22px rgba(17, 24, 39, 0.06);
        }

        .settings-eyebrow {
            color: #198754;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .settings-title {
            color: #1f2937;
            font-weight: 700;
        }

        .settings-subtitle,
        .panel-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .settings-state {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 112px;
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: 700;
            white-space: nowrap;
        }

        .settings-state.is-open {
            color: #0f5132;
            background: #d1e7dd;
        }

        .settings-state.is-closed {
            color: #842029;
            background: #f8d7da;
        }

        .settings-panel {
            height: 100%;
            padding: 24px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #e8edf3;
            box-shadow: 0 8px 22px rgba(17, 24, 39, 0.06);
        }

        .settings-section {
            box-shadow: 0 3px 12px rgba(17, 24, 39, 0.035);
        }

        .settings-wizard-nav {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 18px;
            overflow: hidden;
            border: 1px solid #dce5e3;
            border-radius: 8px;
            background: #fff;
        }

        .wizard-step {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 62px;
            padding: 10px 14px;
            border: 0;
            border-right: 1px solid #e8eeec;
            background: #fff;
            color: #62736f;
            font-size: 13px;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
        }

        .wizard-step:last-child { border-right: 0; }
        .wizard-step:hover { background: #f6faf8; color: #176c59; }
        .wizard-step.is-active { background: #edf6f1; color: #145c4b; }
        .wizard-step-number {
            display: inline-flex;
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            align-items: center;
            justify-content: center;
            border: 1px solid #d4e2dc;
            border-radius: 50%;
            background: #fff;
            color: #687b75;
            font-size: 10px;
        }
        .wizard-step.is-active .wizard-step-number { border-color: #176c59; background: #176c59; color: #fff; }
        .wizard-pane[hidden] { display: none !important; }
        .wizard-actions { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:24px; padding-top:18px; border-top:1px solid #edf1f0; }
        .wizard-actions-end { justify-content:flex-end; }

        .section-heading {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid #edf1f0;
        }

        .section-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            border-radius: 6px;
            background: #e8f3ee;
            color: #176c59;
            font-size: 12px;
            font-weight: 700;
        }

        .status-panel {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .action-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .panel-icon {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex: 0 0 auto;
        }

        .panel-icon-open {
            color: #198754;
            background: #eaf7ef;
        }

        .panel-icon-closed {
            color: #dc3545;
            background: #fff0f1;
        }

        .panel-label {
            color: #198754;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .panel-title {
            color: #1f2937;
            font-weight: 700;
        }

        .access-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e8edf3;
        }

        .access-switch .form-check-input {
            width: 3.4rem;
            height: 1.8rem;
            cursor: pointer;
        }

        .form-panel textarea {
            border-color: #d8dee8;
            border-radius: 8px;
            resize: vertical;
        }

        .form-panel .form-control,
        .form-panel .form-select,
        .form-panel .input-group-text {
            border-color: #d8e1df;
            border-radius: 6px;
        }

        .form-panel .input-group-text {
            color: #526762;
            background: #f3f7f6;
        }

        .form-panel textarea:focus,
        .access-switch .form-check-input:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
        }

        .settings-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 767.98px) {
            .settings-wizard-nav { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .wizard-step:nth-child(3) { border-right:0; }
            .wizard-step:nth-child(-n+2) { border-bottom:1px solid #e8eeec; }
            .wizard-actions { align-items:stretch; flex-direction:column-reverse; }
            .wizard-actions .btn, .wizard-actions > div { width:100%; }
            .settings-actions { justify-content:space-between; }
        }

        @media (max-width: 420px) {
            .wizard-step { gap:6px; padding:9px 8px; font-size:12px; }
            .wizard-step-number { width:26px; height:26px; flex-basis:26px; }
        }

        @media (max-width: 767.98px) {
            .settings-header,
            .status-panel,
            .access-switch {
                align-items: flex-start;
                flex-direction: column;
            }

            .settings-state {
                min-width: auto;
            }

            .settings-panel {
                padding: 20px;
            }

            .legacy-summary {
                margin-left: 0;
            }

            .legacy-section .form-text {
                display: block;
                margin: 10px 0 0 !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = Array.from(document.querySelectorAll('[data-step-target]'));
            const panels = Array.from(document.querySelectorAll('[data-step-panel]'));
            const settingsForm = document.getElementById('applicationControlForm');

            function showStep(stepNumber, focusPanel) {
                panels.forEach(function (panel) {
                    panel.hidden = panel.dataset.stepPanel !== String(stepNumber);
                });
                tabs.forEach(function (tab) {
                    const isActive = tab.dataset.stepTarget === String(stepNumber);
                    tab.classList.toggle('is-active', isActive);
                    tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    tab.tabIndex = isActive ? 0 : -1;
                });

                if (focusPanel) {
                    const panel = document.querySelector('[data-step-panel="' + stepNumber + '"]');
                    panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    panel.querySelector('h5')?.focus({ preventScroll: true });
                }
            }

            function validatePanel(panel) {
                const fields = Array.from(panel.querySelectorAll('input, select, textarea'));
                const invalidField = fields.find(function (field) {
                    return !field.checkValidity();
                });

                if (invalidField) {
                    showStep(panel.dataset.stepPanel, false);
                    invalidField.reportValidity();
                    invalidField.focus();
                    return false;
                }

                return true;
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    showStep(tab.dataset.stepTarget, true);
                });
            });

            document.querySelectorAll('[data-next-step]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const currentPanel = button.closest('[data-step-panel]');
                    if (validatePanel(currentPanel)) {
                        showStep(button.dataset.nextStep, true);
                    }
                });
            });

            document.querySelectorAll('[data-previous-step]').forEach(function (button) {
                button.addEventListener('click', function () {
                    showStep(button.dataset.previousStep, true);
                });
            });

            settingsForm.addEventListener('submit', function (event) {
                for (const panel of panels.filter(function (item) { return item.closest('#applicationControlForm'); })) {
                    if (!validatePanel(panel)) {
                        event.preventDefault();
                        showStep(panel.dataset.stepPanel, true);
                        return;
                    }
                }
            });

            showStep(1, false);
        });
    </script>
</x-app-layout>
