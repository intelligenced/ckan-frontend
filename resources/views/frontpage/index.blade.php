<x-guest-layout>
        <!-- Search Bar -->
    <div class="flex justify-center mb-8">
        <input type="text" placeholder="Search datasets..." class="w-full max-w-2xl p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Catalogues -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Sample Catalogue Card 1 -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-2">Health Data</h2>
            <p class="text-gray-700 mb-4">Explore datasets related to health statistics and services in the Maldives.</p>
            <a href="#" class="text-blue-500 hover:underline">View Catalogue</a>
        </div>

        <!-- Sample Catalogue Card 2 -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-2">Education Data</h2>
            <p class="text-gray-700 mb-4">Access educational statistics and information on schools and universities.</p>
            <a href="#" class="text-blue-500 hover:underline">View Catalogue</a>
        </div>

        <!-- Sample Catalogue Card 3 -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-2">Environmental Data</h2>
            <p class="text-gray-700 mb-4">Find datasets on environmental factors and conservation efforts in the Maldives.</p>
            <a href="#" class="text-blue-500 hover:underline">View Catalogue</a>
        </div>


        <iframe title="Data viewer" width="700" height="400" src="http://ckan.localhost/dataset/islands/resource/ef07c952-8c74-464f-bf7d-77de3e50bcfb/view/ec5e8854-4320-460f-a2d2-1162bc856cd3" frameBorder="0"></iframe>

        <!-- Add more catalogue cards as needed -->
    </div>
</x-guest-layout>
