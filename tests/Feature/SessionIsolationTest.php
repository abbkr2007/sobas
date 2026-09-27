<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Application;
use App\Models\Setting;
use App\Models\User;
use App\Services\AcademicSessionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SessionIsolationTest extends TestCase
{
    use \Tests\Support\SessionFixtures;

    private $current;
    private $previous;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'http://localhost', 'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        URL::forceRootUrl('http://localhost');
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            foreach (['mat_id', 'first_name', 'last_name', 'email', 'phone_number', 'plain_password', 'password'] as $column) {
                $table->string($column)->nullable();
            }
            $table->string('user_type')->default('user');
            $table->unsignedBigInteger('academic_session_id')->nullable();
            $table->timestamps();
        });
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            foreach (['application_id', 'application_type', 'surname', 'firstname', 'middlename', 'email', 'gender', 'state', 'lga', 'photo', 'confirmation_number'] as $column) {
                $table->string($column)->nullable();
            }
            $table->string('status')->default('Pending');
            $table->unsignedBigInteger('academic_session_id')->nullable();
            $table->timestamps();
        });
        (require database_path('migrations/2026_09_26_000001_create_confirmation_fee_payments_table.php'))->up();
        $this->setUpSessionFixtures();
        $this->current = AcademicSession::where('start_year', 2026)->first();
        $this->previous = AcademicSession::create(['start_year' => 2025, 'end_year' => 2026, 'is_active' => false]);
        $this->actingAs(new User(['mat_id' => 'ADMIN', 'email' => 'admin@example.test', 'user_type' => 'admin']));
    }

    private function applicant(AcademicSession $session, string $status, string $serial): Application
    {
        return Application::create([
            'application_id' => 'MAT' . substr($session->start_year, -2) . $serial,
            'academic_session_id' => $session->id, 'status' => $status,
            'surname' => 'Applicant' . $session->start_year,
            'email' => 'applicant@example.test', 'application_type' => 'Matric Science',
        ]);
    }

    public function test_lists_exports_search_and_programmes_follow_settings_not_url_year(): void
    {
        foreach (['applicants' => 'Pending', 'admissions' => 'Admitted', 'confirmations' => 'Confirmed'] as $page => $status) {
            $new = $this->applicant($this->current, $status, (string) (100 + strlen($page)));
            $old = $this->applicant($this->previous, $status, (string) (100 + strlen($page)));
            foreach ([$this->current, $this->previous] as $selected) {
                Setting::setSetting('viewing_academic_session_id', $selected->id, 'integer');
                $expected = $selected->id === $this->current->id ? $new : $old;
                $hidden = $selected->id === $this->current->id ? $old : $new;
                $params = ['year' => 1999, 'session_id' => 'all', 'fee_status' => 'unpaid'];
                $this->getJson(route($page . '.index', $params, false), ['X-Requested-With' => 'XMLHttpRequest'])
                    ->assertOk()->assertJsonPath('recordsTotal', 1)->assertJsonPath('data.0.application_id', $expected->application_id);
                $this->getJson(route($page . '.index', $params + ['search' => ['value' => $hidden->application_id]], false), ['X-Requested-With' => 'XMLHttpRequest'])
                    ->assertOk()->assertJsonPath('recordsFiltered', 0);
                $export = $this->get(route($page . '.export', $params, false))->assertOk()->streamedContent();
                $this->assertStringContainsString($expected->application_id, $export);
                $this->assertStringNotContainsString($hidden->application_id, $export);
                $this->get(route($page . '.index', [], false))->assertOk()->assertDontSee('yearFilter')->assertSee($selected->label);
            }
        }
    }

    public function test_dashboard_and_users_switch_without_changing_registration(): void
    {
        $this->applicant($this->current, 'Pending', '00001');
        $this->applicant($this->previous, 'Pending', '00001');
        $this->applicant($this->previous, 'Admitted', '00002');
        $listedUser = User::create(['mat_id' => 'MAT2500001', 'academic_session_id' => $this->previous->id]);
        $this->get(route('dashboard', [], false))->assertOk()->assertViewHas('currentSessionApplicationCount', 1);
        Setting::setSetting('viewing_academic_session_id', $this->previous->id, 'integer');
        $this->get(route('dashboard', [], false))->assertOk()
            ->assertViewHas('currentSessionApplicationCount', 2)->assertViewHas('sessionTodayCount', 2)->assertViewHas('sessionUserCount', 1);
        $this->getJson(route('users.index', ['academic_session_id' => $this->current->id], false), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertJsonPath('recordsTotal', 1)
            ->assertJsonPath('data.0.DT_RowIndex', 1)
            ->assertJsonPath('data.0.id', $listedUser->id)
            ->assertJsonPath('data.0.mat_id', 'MAT2500001');
        $this->assertSame($this->current->id, app(AcademicSessionService::class)->current()->id);
        $this->assertStringStartsWith('MAT26', User::generateMatId());
    }

    public function test_outside_session_actions_and_bulk_deletion_are_blocked(): void
    {
        $old = $this->applicant($this->previous, 'Admitted', '00001');
        $this->postJson(route('admissions.programme', $old->id, false), ['programme' => 'Remedial Science'])->assertNotFound();
        $this->postJson(route('admissions.confirm', $old->id, false))->assertJson(['success' => false]);
        $this->assertSame('Admitted', $old->fresh()->status);
        $user = User::create(['mat_id' => 'MAT2500001', 'academic_session_id' => $this->previous->id]);
        $this->deleteJson(route('users.destroy-by-session', [], false), ['academic_session_id' => $this->previous->id])->assertStatus(409);
        $this->assertNotNull($user->fresh());
        $this->get(route('users.export', ['academic_session_id' => $this->previous->id], false))->assertStatus(409);
    }

    public function test_settings_separate_viewing_registration_and_historical_session_creation(): void
    {
        $this->post(route('admin.registration.update', [], false), [
            'registration_open' => 1, 'registration_closed_message' => 'Closed',
            'application_fee_naira' => 10000, 'administration_fee_naira' => 1000, 'confirmation_fee_naira' => 10000,
            'academic_session_id' => $this->current->id, 'viewing_academic_session_id' => $this->previous->id,
            'new_session_start_year' => 2024,
        ])->assertSessionHasNoErrors();
        $this->assertSame($this->current->id, app(AcademicSessionService::class)->current()->id);
        $this->assertSame($this->previous->id, app(AcademicSessionService::class)->viewing()->id);
        $this->assertFalse(AcademicSession::where('start_year', 2024)->first()->is_active);
        $this->get(route('admin.registration.index', [], false))->assertOk()->assertSee('Session to view and manage');
    }

    public function test_missing_selected_session_never_falls_back_to_all_records(): void
    {
        $this->applicant($this->current, 'Pending', '00001');
        Setting::setSetting('viewing_academic_session_id', 99999, 'integer');
        $this->getJson(route('applicants.index', [], false), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertJsonPath('recordsTotal', 0);
        $this->get(route('dashboard', [], false))->assertOk()->assertViewHas('currentSessionApplicationCount', 0);
    }
}
