<x-app-layout :assets="[]">
    <div class="container-fluid px-3 px-md-4 payment-history-page">
        <header class="payment-history-header">
            <div>
                <p class="eyebrow">Finance</p>
                <h1>Payment History</h1>
                <p class="header-note">Application and confirmation transactions</p>
            </div>
            <div class="record-count"><strong>{{ $payments->total() }}</strong><span>transactions</span></div>
        </header>

        <form method="GET" action="{{ route('payment-history.index') }}" class="history-filters">
            <label>
                <span>Payment type</span>
                <select name="type" class="form-select">
                    <option value="">All payment types</option>
                    <option value="application" {{ $type === 'application' ? 'selected' : '' }}>Application fee</option>
                    <option value="confirmation" {{ $type === 'confirmation' ? 'selected' : '' }}>Confirmation fee</option>
                </select>
            </label>
            <label>
                <span>Status</span>
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </label>
            <button class="btn btn-dark" type="submit"><i class="fas fa-filter me-2"></i>Filter</button>
            @if($type || $status)
                <a class="btn btn-light" href="{{ route('payment-history.index') }}">Clear</a>
            @endif
        </form>

        <div class="history-table-wrap">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Applicant</th>
                            <th>Matric number</th>
                            <th>Payment</th>
                            <th>Reference</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                            <th class="text-end">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="text-nowrap">{{ optional($payment->created_at ? \Carbon\Carbon::parse($payment->created_at) : null)->format('d M Y, H:i') ?: '—' }}</td>
                                <td>
                                    <div class="applicant-name">{{ trim(($payment->first_name ?? '') . ' ' . ($payment->last_name ?? '')) ?: 'Unknown applicant' }}</div>
                                    <div class="applicant-email">{{ $payment->email ?: '—' }}</div>
                                </td>
                                <td>{{ $payment->matric_number ?: '—' }}</td>
                                <td><span class="type-label {{ $payment->source === 'confirmation' ? 'type-confirmation' : 'type-application' }}">{{ $payment->payment_type }}</span></td>
                                <td class="reference-cell">{{ $payment->reference }}</td>
                                <td class="text-end amount-cell">{{ $payment->currency }} {{ number_format(((int) $payment->amount) / 100, 2) }}</td>
                                <td>
                                    <span class="status-label status-{{ strtolower($payment->status) }}">{{ ucfirst($payment->status) }}</span>
                                </td>
                                <td class="text-end">
                                    @if(strtolower($payment->status) === 'success')
                                        <a class="receipt-link" href="{{ route('payment-history.receipt', [$payment->source, $payment->payment_id]) }}" title="Download receipt" aria-label="Download receipt for {{ $payment->reference }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="empty-state">No payments match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="pagination-wrap">{{ $payments->links() }}</div>
            @endif
        </div>
    </div>

    <style>
        .payment-history-page { max-width: 1500px; padding-top: 24px; padding-bottom: 36px; }
        .payment-history-header { display:flex; align-items:end; justify-content:space-between; gap:20px; padding:0 0 22px; border-bottom:1px solid #dce5e3; }
        .payment-history-header h1 { margin:0; color:#173b35; font-size:26px; font-weight:700; }
        .eyebrow { margin:0 0 5px; color:#287668; font-size:11px; font-weight:700; text-transform:uppercase; }
        .header-note { margin:6px 0 0; color:#687b77; font-size:14px; }
        .record-count { display:flex; align-items:baseline; gap:8px; color:#526762; font-size:13px; }
        .record-count strong { color:#173b35; font-size:22px; }
        .history-filters { display:flex; align-items:end; flex-wrap:wrap; gap:12px; padding:18px 0; }
        .history-filters label { display:grid; gap:5px; min-width:190px; margin:0; color:#526762; font-size:12px; font-weight:600; }
        .history-filters .form-select { min-height:38px; border-color:#d7e1de; }
        .history-table-wrap { border:1px solid #dce5e3; border-radius:6px; background:#fff; overflow:hidden; }
        .history-table-wrap table { min-width:950px; }
        .history-table-wrap thead th { padding:12px 14px; border-bottom:1px solid #dce5e3; background:#f3f7f6; color:#526762; font-size:11px; font-weight:700; text-transform:uppercase; white-space:nowrap; }
        .history-table-wrap tbody td { padding:13px 14px; border-color:#edf1f0; color:#273b37; font-size:13px; }
        .applicant-name { font-weight:600; }
        .applicant-email { margin-top:3px; color:#71817e; font-size:11px; }
        .type-label,.status-label { display:inline-block; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:600; white-space:nowrap; }
        .type-application { color:#285f50; background:#e7f2ed; }
        .type-confirmation { color:#355d75; background:#e9f0f5; }
        .status-success { color:#216b48; background:#e6f4ec; }
        .status-pending { color:#806416; background:#fff4d7; }
        .status-failed { color:#963f3f; background:#f9e9e8; }
        .amount-cell { color:#173b35 !important; font-weight:700; white-space:nowrap; }
        .reference-cell { max-width:190px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-family:monospace; font-size:12px !important; }
        .receipt-link { display:inline-flex; width:32px; height:32px; align-items:center; justify-content:center; border:1px solid #cbd9d5; border-radius:4px; color:#176c59; }
        .receipt-link:hover { color:#fff; background:#176c59; border-color:#176c59; }
        .empty-state { padding:40px !important; color:#687b77 !important; text-align:center; }
        .pagination-wrap { display:flex; justify-content:flex-end; padding:14px; border-top:1px solid #edf1f0; }
        @media (max-width:700px) { .payment-history-header { align-items:start; flex-direction:column; } .history-filters label { min-width:100%; } }
    </style>
</x-app-layout>