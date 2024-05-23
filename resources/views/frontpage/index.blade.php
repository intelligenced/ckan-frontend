<x-guest-layout>
        <!-- Search Bar -->
    <div class="flex justify-center mb-8">
        <input type="text" placeholder="Search datasets..." class="w-full max-w-2xl p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Catalogues -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Sample Catalogue Card 1 -->

        @foreach($groups as $group)
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-2"> {{ $group['title'] }}</h2>
            <p class="text-gray-700 mb-4"> {{ $group['description'] }}</p>
            <a href="#" class="text-blue-500 hover:underline">View Catalogue</a>
        </div>
        @endforeach



        <!-- Add more catalogue cards as needed -->
    </div>
</x-guest-layout>
