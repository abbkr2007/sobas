<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackSplitPaymentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    private function paymentRequest(array $userData = null): Request
    {
        $request = Request::create('/payment/redirect', 'GET', [
            'amount' => 1,
            'subaccount' => 'ACCT_attacker',
            'bearer' => 'account',
        ]);
        $request->setLaravelSession($this->app['session.store']);
        if ($userData !== null) {
            $request->session()->put('user_data', $userData);
        }

        return $request;
    }

    public function test_checkout_uses_fixed_split_and_ignores_browser_and_old_session_amounts()
    {
        config(['paystack.administration_subaccount' => 'ACCT_admin123']);
        $request = $this->paymentRequest(['email' => 'applicant@example.com', 'amount' => 650000]);
        Paystack::shouldReceive('getAuthorizationUrl')->once()->with([
            'email' => 'applicant@example.com',
            'amount' => 1100000,
            'currency' => 'NGN',
            'callback_url' => route('payment.callback'),
            'subaccount' => 'ACCT_admin123',
            'transaction_charge' => 1000000,
            'bearer' => 'subaccount',
        ])->andReturnSelf();
        Paystack::shouldReceive('redirectNow')->once()->andReturn(redirect('https://checkout.paystack.com/test'));

        $response = (new RegisteredUserController)->redirectToGateway($request);

        $this->assertSame('https://checkout.paystack.com/test', $response->getTargetUrl());
        $this->assertSame(1100000, $request->session()->get('user_data.amount'));
    }

    public function test_checkout_uses_fee_values_saved_in_settings()
    {
        \App\Models\Setting::setSetting('application_fee', 250000, 'integer');
        \App\Models\Setting::setSetting('administration_fee', 50000, 'integer');
        config(['paystack.administration_subaccount' => 'ACCT_admin123']);
        $request = $this->paymentRequest(['email' => 'applicant@example.com', 'amount' => 1100000]);
        Paystack::shouldReceive('getAuthorizationUrl')->once()->with([
            'email' => 'applicant@example.com',
            'amount' => 300000,
            'currency' => 'NGN',
            'callback_url' => route('payment.callback'),
            'subaccount' => 'ACCT_admin123',
            'transaction_charge' => 250000,
            'bearer' => 'subaccount',
        ])->andReturnSelf();
        Paystack::shouldReceive('redirectNow')->once()->andReturn(redirect('https://checkout.paystack.com/test'));

        (new RegisteredUserController)->redirectToGateway($request);

        $this->assertSame(300000, $request->session()->get('user_data.amount'));
    }

    /** @dataProvider invalidSubaccounts */
    public function test_checkout_blocks_missing_or_invalid_subaccounts($subaccount)
    {
        config(['paystack.administration_subaccount' => $subaccount]);
        Paystack::shouldReceive('getAuthorizationUrl')->never();
        $request = $this->paymentRequest(['email' => 'applicant@example.com', 'amount' => 1100000]);

        $response = (new RegisteredUserController)->redirectToGateway($request);

        $this->assertSame(url('/'), $response->getTargetUrl());
        $this->assertSame('Payments are temporarily unavailable. Please try again later.', session('error'));
    }

    public static function invalidSubaccounts(): array
    {
        return [[null], [''], ['invalid'], ['ACCT_']];
    }

    public function test_checkout_requires_application_session()
    {
        Paystack::shouldReceive('getAuthorizationUrl')->never();
        $response = (new RegisteredUserController)->redirectToGateway($this->paymentRequest());
        $this->assertSame(url('/'), $response->getTargetUrl());
        $this->assertSame('Please complete the application details before paying.', session('error'));
    }

    /** @dataProvider mismatchedTransactions */
    public function test_callback_rejects_mismatched_payments_before_creating_an_applicant($amount, $currency, $email)
    {
        $request = $this->paymentRequest(['email' => 'applicant@example.com', 'amount' => 1100000]);
        Paystack::shouldReceive('getPaymentData')->once()->andReturn([
            'status' => true,
            'data' => [
                'status' => 'success', 'amount' => $amount, 'currency' => $currency,
                'customer' => ['email' => $email],
            ],
        ]);

        $response = (new RegisteredUserController)->handleGatewayCallback($request);

        $this->assertSame(url('/'), $response->getTargetUrl());
        $this->assertSame('Payment details do not match your application. Please contact support.', session('error'));
        $this->assertNotNull($request->session()->get('user_data'));
    }

    public static function mismatchedTransactions(): array
    {
        return [
            [650000, 'NGN', 'applicant@example.com'],
            [1100000, 'USD', 'applicant@example.com'],
            [1100000, 'NGN', 'another@example.com'],
        ];
    }
}
