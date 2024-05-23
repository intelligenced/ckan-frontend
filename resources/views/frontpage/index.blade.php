<x-guest-layout>
    <!-- Search Bar -->
    <div class="flex justify-center my-10">
        <input type="text" placeholder="Search datasets..." class="w-full max-w-2xl p-4 text-gray-700 leading-tight border-2 border-gray-300 rounded-lg shadow focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200 ease-in-out">
    </div>

    <!-- Catalogues -->
    <div class="px-4 md:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($groups as $group)
            <div class="bg-white p-6 rounded-lg shadow-lg transition duration-300 ease-in-out hover:shadow-xl">
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">{{ $group['title'] }}</h2>
                <p class="text-gray-600 mb-5">{{ $group['description'] }}</p>
                <a href="{{ route('frontpage.category', ['group' => $group['name']]) }}" class="inline-block text-blue-600 hover:text-blue-800 hover:underline">View Catalogue</a>
            </div>
        @endforeach

        </div>
    </div>
</x-guest-layout>
