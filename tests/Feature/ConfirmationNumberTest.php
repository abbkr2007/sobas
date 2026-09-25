<?php

namespace Tests\Feature;

use App\Http\Controllers\ApplicantController;
use App\Models\Application;
use App\Services\ConfirmationNumber;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ConfirmationNumberTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->string('application_type');
            $table->string('status');
            $table->timestamps();
        });
        $migration = require database_path('migrations/2026_09_25_000001_add_confirmation_numbers.php');
        $migration->up();
        Carbon::setTestNow(Carbon::parse('2026-09-25 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        DB::disconnect('sqlite');
        parent::tearDown();
    }

    private function applicant(string $programme, string $status = 'Confirmed'): Application
    {
        return Application::create([
            'application_id' => 'MAT2500123',
            'application_type' => $programme,
            'status' => $status,
        ]);
    }

    public function test_separate_programme_sequences_and_stable_reprints(): void
    {
        $numbers = new ConfirmationNumber;
        $science = $this->applicant('Matric Science');
        $this->assertSame('26100001', $numbers->forApplication($science));
        $this->assertSame('26100002', $numbers->forApplication($this->applicant('matric_science')));
        $this->assertSame('2626001', $numbers->forApplication($this->applicant('Remedial Science')));
        $this->assertSame('2626002', $numbers->forApplication($this->applicant('Remedial Arts')));
        $this->assertSame('26100001', $numbers->forApplication($science));
        $this->assertSame('MAT2500123', $science->fresh()->application_id);

        Carbon::setTestNow(Carbon::parse('2027-01-01'));
        $this->assertSame('27100001', $numbers->forApplication($this->applicant('Matric Science')));
        $this->assertSame('2726001', $numbers->forApplication($this->applicant('Remedial Science')));
        $this->assertSame('26100001', $numbers->forApplication($science));
    }

    public function test_only_confirmation_letter_uses_new_number(): void
    {
        $applicant = $this->applicant('Matric Science');
        $controller = new ApplicantController;
        $confirmation = new \ReflectionMethod($controller, 'generateConfirmationLetterPDF');
        $confirmation->setAccessible(true);
        $html = $confirmation->invoke($controller, $applicant, 'Test Student')->getContent();
        $this->assertSame(2, substr_count($html, '26100001'));
        $this->assertStringNotContainsString('MAT2500123', $html);

        $admission = new \ReflectionMethod($controller, 'generateAdmissionLetterPDF');
        $admission->setAccessible(true);
        $html = $admission->invoke($controller, $applicant, 'Test Student')->getContent();
        $this->assertStringContainsString('MAT2500123', $html);
        $this->assertStringNotContainsString('26100001', $html);
    }

    public function test_unconfirmed_applicants_cannot_consume_a_serial(): void
    {
        $this->expectException(\LogicException::class);
        (new ConfirmationNumber)->forApplication($this->applicant('Matric Science', 'Admitted'));
    }

    public function test_other_programmes_keep_existing_numbering(): void
    {
        $this->assertNull((new ConfirmationNumber)->forApplication($this->applicant('Matric Arts')));
        $this->assertSame(0, DB::table('confirmation_sequences')->count());
    }
}
