<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PaymentHistoryTest extends TestCase
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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
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
            $table->timestamps();
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->unsignedBigInteger('amount');
            $table->string('currency', 3);
            $table->string('status');
            $table->string('reference')->unique();
            $table->timestamps();
        });

        $paymentMigration = require database_path('migrations/2026_09_26_000001_create_confirmation_fee_payments_table.php');
        $paymentMigration->up();
    }

    public function test_admin_history_combines_both_payment_types_and_downloads_receipts(): void
    {
        $userId = DB::table('users')->insertGetId([
            'mat_id' => 'MAT2600001',
            'first_name' => 'Ada',
            'last_name' => 'Applicant',
            'email' => 'ada@example.com',
            'user_type' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $applicationId = DB::table('applications')->insertGetId([
            'application_id' => 'MAT2600001',
            'application_type' => 'Matric Science',
            'status' => 'Admitted',
            'email' => 'ada@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $applicationPaymentId = DB::table('payments')->insertGetId([
            'user_id' => $userId,
            'transaction_id' => 'reg-txn-1',
            'amount' => 1100000,
            'currency' => 'NGN',
            'status' => 'success',
            'reference' => 'REG-REF-1',
            'created_at' => now()->subMinute(),
            'updated_at' => now()->subMinute(),
        ]);
        $confirmationPaymentId = DB::table('confirmation_fee_payments')->insertGetId([
            'application_id' => $applicationId,
            'user_id' => $userId,
            'transaction_id' => 'conf-txn-1',
            'amount' => 1100000,
            'currency' => 'NGN',
            'status' => 'success',
            'reference' => 'CONF-REF-1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->adminUser());

        $this->get(route('payment-history.index', [], false))
            ->assertOk()
            ->assertSee('Payment History')
            ->assertSee(route('payment-history.index'))
            ->assertSee('Application fee')
            ->assertSee('Confirmation fee')
            ->assertSee('REG-REF-1')
            ->assertSee('CONF-REF-1');

        foreach ([['application', $applicationPaymentId], ['confirmation', $confirmationPaymentId]] as [$source, $paymentId]) {
            $response = $this->get(route('payment-history.receipt', [$source, $paymentId], false));
            $response->assertOk()->assertHeader('content-type', 'application/pdf');
            $this->assertStringStartsWith('%PDF-', $response->getContent());
        }
    }

    public function test_receipt_is_not_available_for_unpaid_payment_and_non_admins_are_denied(): void
    {
        $userId = DB::table('users')->insertGetId([
            'mat_id' => 'MAT2600002',
            'first_name' => 'Sam',
            'last_name' => 'Pending',
            'email' => 'sam@example.com',
            'user_type' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $applicationId = DB::table('applications')->insertGetId([
            'application_id' => 'MAT2600002',
            'application_type' => 'Matric Science',
            'status' => 'Admitted',
            'email' => 'sam@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $paymentId = DB::table('confirmation_fee_payments')->insertGetId([
            'application_id' => $applicationId,
            'user_id' => $userId,
            'amount' => 1100000,
            'currency' => 'NGN',
            'status' => 'pending',
            'reference' => 'CONF-PENDING',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->adminUser())
            ->get(route('payment-history.receipt', ['confirmation', $paymentId], false))
            ->assertNotFound();

        $applicant = new User(['mat_id' => 'MAT2600002', 'email' => 'sam@example.com', 'user_type' => 'user']);
        $applicant->id = $userId;
        $this->actingAs($applicant)
            ->get(route('payment-history.index', [], false))
            ->assertForbidden();
    }

    public function test_applicant_sees_only_own_payment_history_and_receipts(): void
    {
        $applicantId = DB::table('users')->insertGetId([
            'mat_id' => 'MAT2600003',
            'first_name' => 'Alex',
            'last_name' => 'Applicant',
            'email' => 'alex@example.com',
            'user_type' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $otherUserId = DB::table('users')->insertGetId([
            'mat_id' => 'MAT2600004',
            'first_name' => 'Other',
            'last_name' => 'Applicant',
            'email' => 'other@example.com',
            'user_type' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $applicationId = DB::table('applications')->insertGetId([
            'application_id' => 'MAT2600003',
            'application_type' => 'Matric Science',
            'status' => 'Admitted',
            'email' => 'alex@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payments')->insert([
            'user_id' => $applicantId,
            'transaction_id' => 'own-app-transaction',
            'amount' => 1100000,
            'currency' => 'NGN',
            'status' => 'success',
            'reference' => 'OWN-APP-REF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('confirmation_fee_payments')->insert([
            'application_id' => $applicationId,
            'user_id' => $applicantId,
            'transaction_id' => 'own-conf-transaction',
            'amount' => 1100000,
            'currency' => 'NGN',
            'status' => 'success',
            'reference' => 'OWN-CONF-REF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payments')->insert([
            'user_id' => $otherUserId,
            'transaction_id' => 'other-transaction',
            'amount' => 1100000,
            'currency' => 'NGN',
            'status' => 'success',
            'reference' => 'OTHER-REF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $applicant = new User(['mat_id' => 'MAT2600003', 'email' => 'alex@example.com', 'user_type' => 'user']);
        $applicant->id = $applicantId;
        $this->actingAs($applicant);

        $this->get(route('my-payment-history.index', [], false))
            ->assertOk()
            ->assertSee('Your application and confirmation transactions')
            ->assertSee(route('my-payment-history.index'))
            ->assertSee('OWN-APP-REF')
            ->assertSee('OWN-CONF-REF')
            ->assertDontSee('OTHER-REF');

        $confirmationPaymentId = DB::table('confirmation_fee_payments')->where('reference', 'OWN-CONF-REF')->value('id');
        $otherPaymentId = DB::table('payments')->where('reference', 'OTHER-REF')->value('id');
        $this->get(route('my-payment-history.receipt', ['confirmation', $confirmationPaymentId], false))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->get(route('my-payment-history.receipt', ['application', $otherPaymentId], false))
            ->assertNotFound();
    }

    private function adminUser(): User
    {
        $admin = new User(['mat_id' => 'ADMIN1', 'email' => 'admin@example.com', 'user_type' => 'admin']);
        $admin->id = 99;

        return $admin;
    }
}