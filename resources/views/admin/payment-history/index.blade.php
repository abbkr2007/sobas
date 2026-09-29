<x-app-layout :assets="[]">
    <div class="container-fluid px-3 px-md-4 payment-history-page">
        <header class="payment-history-header">
            <div>
                <p class="eyebrow">Finance</p>
                <h1>Payment History</h1>
                <p class="header-note">{{ $isAdmin ? 'Application and confirmation transactions' : 'Your application and confirmation transactions' }}</p>
            </div>
            <div class="record-count"><strong id="history-total">{{ $payments->total() }}</strong><span>transactions</span></div>
        </header>

        <form method="GET" action="{{ route($isAdmin ? 'payment-history.index' : 'my-payment-history.index') }}" class="history-filters">
            @if($isAdmin)
                <p class="mb-0">Session: {{ $viewingSession ? $viewingSession->label : 'None selected' }} &middot; <a href="{{ route('admin.registration.index') }}">Change in Settings</a></p>
            @endif
            <label class="history-search">
                <span>Search payments</span>
                <input type="search" name="search" class="form-control" value="{{ $search }}" maxlength="200" placeholder="Name, email, matric number or reference" autocomplete="off" aria-controls="history-results">
            </label>
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
            <a class="btn btn-light history-clear" href="{{ route($isAdmin ? 'payment-history.index' : 'my-payment-history.index') }}">Clear</a>
        </form>

        <p id="history-feedback" role="status" aria-live="polite" class="text-muted"></p>
        <div id="history-results" aria-busy="false">
            @include('admin.payment-history.results')
        </div>
    </div>
    <style>
        .history-search { flex:1; min-width:280px !important; }
        .pagination-wrap .pagination { margin-bottom:0; }
        #history-results[aria-busy="true"] { opacity:0.6; }
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
        .pagination-wrap { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; padding:14px; border-top:1px solid #edf1f0; }
        @media (max-width:700px) { .payment-history-header { align-items:start; flex-direction:column; } .history-filters label { min-width:100%; } }
    </style>
    <script>
        (() => {
            const form = document.querySelector('.history-filters');
            const results = document.getElementById('history-results');
            const feedback = document.getElementById('history-feedback');
            const search = form.elements.search;
            let timer;
            let controller;
            let version = 0;

            function cancelPending() {
                clearTimeout(timer);
                version++;
                if (controller) controller.abort();
            }

            function filterUrl() {
                const url = new URL(form.action);
                new FormData(form).forEach((value, key) => {
                    if (value) url.searchParams.set(key, value);
                });
                return url;
            }

            async function load(url, updateHistory = true) {
                cancelPending();
                const requestVersion = version;
                controller = new AbortController();
                results.setAttribute('aria-busy', 'true');
                feedback.textContent = 'Loading payments…';
                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        signal: controller.signal,
                    });
                    if (!response.ok) throw new Error('Unable to load payments');
                    const data = await response.json();
                    if (requestVersion !== version) return;
                    results.innerHTML = data.html;
                    document.getElementById('history-total').textContent = data.total;
                    feedback.textContent = data.total + ' matching transactions.';
                    if (updateHistory) window.history.pushState({}, '', url);
                } catch (error) {
                    if (requestVersion !== version || error.name === 'AbortError') return;
                    feedback.textContent = 'Could not update payments. Please press Filter to try again.';
                } finally {
                    if (requestVersion === version) results.setAttribute('aria-busy', 'false');
                }
            }

            search.addEventListener('input', () => {
                cancelPending();
                results.setAttribute('aria-busy', 'true');
                feedback.textContent = 'Searching payments…';
                timer = setTimeout(() => load(filterUrl()), 300);
            });
            form.addEventListener('submit', event => {
                event.preventDefault();
                load(filterUrl());
            });
            form.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', () => load(filterUrl()));
            });
            function normalClick(event) {
                return event.button === 0 && !event.ctrlKey && !event.metaKey && !event.shiftKey && !event.altKey;
            }
            form.querySelector('.history-clear').addEventListener('click', event => {
                if (!normalClick(event)) return;
                event.preventDefault();
                search.value = '';
                form.elements.type.value = '';
                form.elements.status.value = '';
                load(filterUrl());
            });
            results.addEventListener('click', event => {
                const link = event.target.closest('.pagination a');
                if (!link || !normalClick(event)) return;
                event.preventDefault();
                load(link.href);
            });
            window.addEventListener('popstate', () => {
                const url = new URL(window.location.href);
                ['search', 'type', 'status'].forEach(name => {
                    form.elements[name].value = url.searchParams.get(name) || '';
                });
                load(url, false);
            });
        })();
    </script>
</x-app-layout>