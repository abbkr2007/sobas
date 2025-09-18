<x-app-layout :assets="$assets ?? []">
    <div class="container py-6">
        <h2 class="text-xl font-semibold mb-4">Bulk Create Users</h2>

        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="" method="POST" class="max-w-md">
            @csrf

            <div class="mb-4">
                <label for="count" class="block text-sm font-medium text-gray-700 mb-1">
                    Number of Users to Create
                </label>
                <input 
                    type="number" 
                    name="count" 
                    id="count" 
                    class="form-control w-full" 
                    placeholder="Enter number of users" 
                    min="1" 
                    max="1000" 
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Create Users
            </button>
        </form>
    </div>
</x-app-layout>
