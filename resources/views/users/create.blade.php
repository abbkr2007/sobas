<x-app-layout :assets="$assets ?? []">
<div class="container">/ 
    <br /> <br /><br /><br />
    <h3>Generate Users</h3>
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
