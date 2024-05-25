<x-guest-layout>
    <div class="min-h-screen bg-white">
        <!-- Introduction and Search Section -->
        <div class="p-12 bg-teal-50 border-b-4 border-teal-600">
            <h1 class="font-semibold text-3xl mb-2">Find Open Data</h1>
            <p class="text-lg mb-6">
                Discover data published by government, local authorities, and public bodies to build products and services.
            </p>
            <!-- Search Bar -->
            <div class="relative max-w-lg">
                <form action="{{ route('frontpage.explore') }}?name='{{ request('name')}}'" method="GET" class="mb-4">
                    <input type="text" name="name" placeholder="Search datasets..." value="{{ request('name') }}"  class="w-full p-3 text-gray-700 leading-tight border border-teal-600 rounded-lg shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200 ease-in-out">
                    <button type="submit" class="absolute inset-y-0 right-0 px-4 text-white bg-teal-600 rounded-r-lg hover:bg-teal-800 focus:outline-none transition duration-200">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Data Categories -->
        <div class="px-4 md:px-6 lg:px-8 my-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($groups as $group)
                    <a href="{{ route('frontpage.explore', ['group' => $group['name']]) }}" class="block hover:bg-gray-50 transition duration-300 ease-in-out shadow-sm hover:shadow-md rounded-lg">
                        <div class="p-4 flex items-center space-x-4"> 
                            <img src="{{ $group['image_display_url'] }}" alt="{{ $group['title'] }}" class="h-12 w-12"> 
                            <div>
                                <h2 class="text-lg font-semibold text-teal-800 underline ">{{ $group['title'] }}</h2>
                                <p class="text-md text-gray-600">{{ $group['description'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>
