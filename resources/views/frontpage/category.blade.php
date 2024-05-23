<x-guest-layout>
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md p-4">
            <h2 class="text-2xl font-semibold mb-4">Categories</h2>
            <ul>
                @foreach($groups as $group)
                    <li class="mb-2">
                        <a href="{{ route('frontpage.category', ['group' => $group['name']]) }}" class="text-blue-600 hover:underline">{{ $group['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-4">Datasets</h1>
            @foreach($datasets as $dataset)
                <div class="bg-white p-6 rounded-lg shadow-lg mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-3">{{ $dataset['title'] }}</h2>
                    <p class="text-gray-600 mb-5">{{ $dataset['notes'] }}</p>
                    <a href="{{ route('frontpage.data', ['id' => $dataset['id']]) }}" class="inline-block text-blue-600 hover:text-blue-800 hover:underline">View Dataset</a>
                </div>
            @endforeach
        </main>
    </div>
</x-guest-layout>
