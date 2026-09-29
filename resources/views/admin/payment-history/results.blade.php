        <div class="history-table-wrap">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">S/N</th><th>Date</th>
                            <th>Applicant</th>
                            <th>Matric number</th>
                            <th>Reference</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="serial-number">{{ $payments->firstItem() + $loop->index }}</td><td class="text-nowrap">{{ optional($payment->created_at ? \Carbon\Carbon::parse($payment->created_at) : null)->format('d M Y, H:i') ?: '—' }}</td>
                                <td>
                                    <div class="applicant-name">{{ trim(($payment->first_name ?? '') . ' ' . ($payment->last_name ?? '')) ?: 'Unknown applicant' }}</div>
                                    <div class="applicant-email">{{ $payment->email ?: '—' }}</div>
                                </td>
                                <td>{{ $payment->matric_number ?: '—' }}</td>
                                <td class="reference-cell">{{ $payment->reference }}</td>
                                <td class="text-end amount-cell">{{ $payment->currency }} {{ number_format(((int) $payment->amount) / 100, 2) }}</td>
                                <td class="text-end">
                                    @if(strtolower($payment->status) === 'success')
                                        <a class="receipt-link" href="{{ route($isAdmin ? 'payment-history.receipt' : 'my-payment-history.receipt', [$payment->source, $payment->payment_id]) }}" title="Download receipt" aria-label="Download receipt for {{ $payment->reference }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="empty-state">No payments match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">
                <p class="mb-0 text-muted">Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} transactions</p>
                {{ $payments->links('pagination::bootstrap-4') }}
            </div>
        </div>
