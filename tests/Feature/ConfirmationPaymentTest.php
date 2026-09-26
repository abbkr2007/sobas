<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ConfirmationFeePayment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Unicodeveloper\Paystack\Facades\Paystack;

class ConfirmationPaymentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('mat_id')->unique();
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('user_type')->default('user');
            $table->timestamps();
        });
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->string('application_type')->nullable();
            $table->string('status');
            $table->string('email');
            $table->string('surname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('gender')->nullable();
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->timestamps();
        });
        $confirmationMigration = require database_path('migrations/2026_09_25_000001_add_confirmation_numbers.php');
        $confirmationMigration->up();
        $paymentMigration = require database_path('migrations/2026_09_26_000001_create_confirmation_fee_payments_table.php');
        $paymentMigration->up();
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function test_verified_payment_is_recorded_without_confirming_the_admission(): void
    {
        $application = $this->admittedApplication();
        $user = $this->applicantUser();
        $payment = ConfirmationFeePayment::create([
            'application_id' => $application->id,
            'user_id' => $user->id,
            'reference' => 'CONF-test-reference',
            'amount' => 1100000,
            'currency' => 'NGN',
            'status' => 'pending',
        ]);
        $this->actingAs($user);
        $this->withSession(['confirmation_payment' => [
            'payment_id' => $payment->id,
            'reference' => $payment->reference,
            'user_id' => $user->id,
        ]]);
        Paystack::shouldReceive('getPaymentData')->once()->andReturn([
            'status' => true,
            'data' => [
                'id' => 12345,
                'reference' => 'CONF-test-reference',
                'status' => 'success',
                'amount' => 1100000,
                'currency' => 'NGN',
                'customer' => ['email' => 'applicant@example.com'],
            ],
        ]);

        $response = $this->get(route('confirmation-payment.callback', [], false));
        $this->assertSame(302, $response->getStatusCode(), $response->getContent());

        $this->assertSame('success', $payment->fresh()->status);
        $this->assertSame('Admitted', $application->fresh()->status);
    }

    public function test_admitted_applicant_checkout_uses_the_configured_confirmation_fee(): void
    {
        $application = $this->admittedApplication();
        Setting::setSetting('confirmation_fee', 1250000, 'integer');
        Setting::setSetting('administration_fee', 125020, 'integer');
        $user = $this->applicantUser();
        $this->actingAs($user);
        Paystack::shouldReceive('getAuthorizationUrl')->once()->with(\Mockery::on(function ($payload) {
            return $payload['email'] === 'applicant@example.com'
                && $payload['amount'] === 1375020
                && $payload['currency'] === 'NGN'
                && strpos($payload['reference'], 'CONF-') === 0
                && $payload['callback_url'] === route('confirmation-payment.callback');
        }))->andReturnSelf();
        Paystack::shouldReceive('redirectNow')->once()->andReturn(redirect('https://checkout.paystack.com/confirmation'));

        $response = $this->post(route('confirmation-payment.checkout', [], false));

        $response->assertRedirect('https://checkout.paystack.com/confirmation');
        $this->assertDatabaseHas('confirmation_fee_payments', [
            'application_id' => $application->id,
            'user_id' => $user->id,
            'amount' => 1375020,
            'status' => 'pending',
        ]);
        $this->assertSame('Admitted', $application->fresh()->status);
    }

    public function test_applicants_cannot_confirm_admissions_through_status_endpoints(): void
    {
        $application = $this->admittedApplication();
        $this->actingAs($this->applicantUser());

        $confirmResponse = $this->postJson(route('admissions.confirm', $application->id, false));
        $this->assertSame(403, $confirmResponse->getStatusCode(), $confirmResponse->getContent());
        $statusResponse = $this->postJson(route('applicants.update-status', $application->id, false), ['status' => 'Confirmed']);
        $this->assertSame(403, $statusResponse->getStatusCode(), $statusResponse->getContent());

        $this->assertSame('Admitted', $application->fresh()->status);
    }

    public function test_admin_settings_save_all_fee_values_in_kobo(): void
    {
        $admin = new User(['mat_id' => 'ADMIN1', 'email' => 'admin@example.com', 'user_type' => 'admin']);
        $admin->id = 8;
        $this->actingAs($admin);

        $this->post(route('admin.registration.update', [], false), [
            'registration_open' => '1',
            'registration_closed_message' => 'Applications are closed.',
            'application_fee_naira' => '12500.50',
            'administration_fee_naira' => '1250.20',
            'confirmation_fee_naira' => '10000.00',
        ]);

        $this->assertSame(1250050, Setting::getSetting('application_fee'));
        $this->assertSame(125020, Setting::getSetting('administration_fee'));
        $this->assertSame(1000000, Setting::getSetting('confirmation_fee'));
    }

    public function test_admin_cannot_set_confirmed_through_the_generic_status_editor(): void
    {
        $application = $this->admittedApplication();
        $admin = new User(['mat_id' => 'ADMIN1', 'email' => 'admin@example.com', 'user_type' => 'admin']);
        $admin->id = 8;
        $this->actingAs($admin);

        $this->postJson(route('applicants.update-field', $application->id, false), [
            'field' => 'status',
            'value' => 'Confirmed',
        ])->assertStatus(422);

        $this->assertSame('Admitted', $application->fresh()->status);
    }

    public function test_admin_can_confirm_an_admitted_applicant_through_the_admissions_action(): void
    {
        $application = $this->admittedApplication();
        $application->application_type = 'Matric Arts';
        $application->save();
        $admin = new User(['mat_id' => 'ADMIN1', 'email' => 'admin@example.com', 'user_type' => 'admin']);
        $admin->id = 8;
        $this->actingAs($admin);

        $this->postJson(route('admissions.confirm', $application->id, false))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame('Confirmed', $application->fresh()->status);
    }

    private function admittedApplication(): Application
    {
        return Application::create([
            'application_id' => 'MAT2600001',
            'application_type' => 'Matric Science',
            'status' => 'Admitted',
            'email' => 'applicant@example.com',
        ]);
    }

    private function applicantUser(): User
    {
        return User::create([
            'mat_id' => 'MAT2600001',
            'email' => 'applicant@example.com',
            'user_type' => 'user',
            'password' => 'test-password',
        ]);
    }
}