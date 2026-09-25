<x-app-layout :assets="$assets ?? []">
<div class="container">/ 
    <br /> <br /><br /><br />
    <h3>Generate Users</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    <div class="alert alert-info d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
        <span>Active session: <strong>{{ optional($activeSession)->label ?? 'Not configured' }}</strong>.</span>
        <a href="{{ route('admin.registration.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-calendar-alt me-1"></i>Open Application Control
        </a>
    </div>
    <form action="{{ route('bulk-users.create') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Number of Users to Generate</label>
            <input type="number" name="count" class="form-control" placeholder="e.g. 100" min="1" required>
        </div>
        <button type="submit" class="btn btn-success">Generate</button>
    </form>
</div>
</x-app-layout>
