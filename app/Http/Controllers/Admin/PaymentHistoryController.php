<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $type = in_array($request->input('type'), ['application', 'confirmation'], true)
            ? $request->input('type')
            : null;
        $status = in_array($request->input('status'), ['success', 'pending', 'failed'], true)
            ? $request->input('status')
            : null;

        $applicationPayments = DB::table('payments')
            ->leftJoin('users', 'users.id', '=', 'payments.user_id')
            ->selectRaw("payments.id as payment_id, 'application' as source, 'Application fee' as payment_type, users.first_name, users.last_name, users.mat_id as matric_number, users.email, payments.reference, payments.transaction_id, payments.amount, payments.currency, payments.status, payments.created_at");

        $confirmationPayments = DB::table('confirmation_fee_payments')
            ->leftJoin('users', 'users.id', '=', 'confirmation_fee_payments.user_id')
            ->leftJoin('applications', 'applications.id', '=', 'confirmation_fee_payments.application_id')
            ->selectRaw("confirmation_fee_payments.id as payment_id, 'confirmation' as source, 'Confirmation fee' as payment_type, users.first_name, users.last_name, users.mat_id as matric_number, applications.email, confirmation_fee_payments.reference, confirmation_fee_payments.transaction_id, confirmation_fee_payments.amount, confirmation_fee_payments.currency, confirmation_fee_payments.status, confirmation_fee_payments.created_at");

        $historyQuery = DB::query()->fromSub(
            $applicationPayments->unionAll($confirmationPayments),
            'payment_history'
        );

        if ($type) {
            $historyQuery->where('source', $type);
        }
        if ($status) {
            $historyQuery->where('status', $status);
        }

        $payments = $historyQuery->orderByDesc('created_at')->paginate(25)->appends($request->query());

        return view('admin.payment-history.index', compact('payments', 'type', 'status'));
    }

    public function receipt(Request $request, string $source, int $id)
    {
        $this->authorizeAdmin();

        if ($source === 'application') {
            $payment = Payment::query()
                ->leftJoin('users', 'users.id', '=', 'payments.user_id')
                ->where('payments.id', $id)
                ->where('payments.status', 'success')
                ->selectRaw("payments.*, users.first_name, users.last_name, users.mat_id as matric_number, users.email")
                ->first();
            $paymentType = 'Application fee';
        } elseif ($source === 'confirmation') {
            $payment = DB::table('confirmation_fee_payments')
                ->leftJoin('users', 'users.id', '=', 'confirmation_fee_payments.user_id')
                ->leftJoin('applications', 'applications.id', '=', 'confirmation_fee_payments.application_id')
                ->where('confirmation_fee_payments.id', $id)
                ->where('confirmation_fee_payments.status', 'success')
                ->selectRaw("confirmation_fee_payments.*, users.first_name, users.last_name, users.mat_id as matric_number, applications.email")
                ->first();
            $paymentType = 'Confirmation fee';
        } else {
            abort(404);
        }

        abort_unless($payment, 404);

        return app('dompdf.wrapper')
            ->loadView('admin.payment-history.receipt', compact('payment', 'paymentType'))
            ->setPaper('a6', 'portrait')
            ->download('receipt-' . preg_replace('/[^A-Za-z0-9_-]/', '', $payment->reference) . '.pdf');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->user_type === 'admin', 403);
    }
}