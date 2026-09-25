<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OfferedProgrammeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'http://localhost']);
        \Illuminate\Support\Facades\URL::forceRootUrl('http://localhost');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->string('application_type');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function test_admin_can_change_programme_without_changing_matric_or_status(): void
    {
        $this->actingAs(new User(['user_type' => 'admin']));
        $application = $this->applicant();
        $this->postJson(route('admissions.programme', $application->id, false), ['programme' => 'Remedial Science'])
            ->assertOk()->assertJson(['success' => true]);
        $application->refresh();
        $this->assertSame('Remedial Science', $application->application_type);
        $this->assertSame('MAT2500123', $application->application_id);
        $this->assertSame('Admitted', $application->status);
    }

    public function test_invalid_programme_and_confirmed_applicants_are_rejected(): void
    {
        $this->actingAs(new User(['user_type' => 'admin']));
        $application = $this->applicant();
        $url = route('admissions.programme', $application->id, false);
        $this->postJson($url, ['programme' => 'Invalid'])->assertStatus(422);
        $application->update(['status' => 'Confirmed']);
        $this->postJson($url, ['programme' => 'Remedial Science'])->assertStatus(422);
        $this->assertSame('Matric Science', $application->fresh()->application_type);
    }

    public function test_guests_and_non_admins_cannot_change_programme(): void
    {
        $application = $this->applicant();
        $url = route('admissions.programme', $application->id, false);
        $this->postJson($url, ['programme' => 'Remedial Science'])->assertStatus(401);
        $this->actingAs(new User(['user_type' => 'user']));
        $this->postJson($url, ['programme' => 'Remedial Science'])->assertForbidden();
        $this->assertSame('Matric Science', $application->fresh()->application_type);
    }

    private function applicant(): Application
    {
        return Application::create([
            'application_id' => 'MAT2500123', 'application_type' => 'Matric Science', 'status' => 'Admitted',
        ]);
    }
}
