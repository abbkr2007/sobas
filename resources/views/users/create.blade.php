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
    <form action="{{ route('academic-sessions.create') }}" method="POST" class="mb-4">
        @csrf
        <label for="start_year">Create academic session</label>
        <div class="input-group">
            <input type="number" name="start_year" id="start_year" class="form-control" min="2000" max="2100" placeholder="2026" required>
            <button type="submit" class="btn btn-outline-primary">Create Session</button>
        </div>
    </form>
    <form action="{{ route('bulk-users.create') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="academic_session_id">Academic session</label>
            <select name="academic_session_id" id="academic_session_id" class="form-control" required>
                <option value="">Select session</option>
                @foreach ($sessions as $session)
                    <option value="{{ $session->id }}" {{ optional($activeSession)->id === $session->id ? 'selected' : '' }}>
                        {{ $session->label }}{{ $session->is_active ? ' (Active)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Number of Users to Generate</label>
            <input type="number" name="count" class="form-control" placeholder="e.g. 100" min="1" required>
        </div>
        <button type="submit" class="btn btn-success">Generate</button>
    </form>
</div>
</x-app-layout>
