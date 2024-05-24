<x-guest-layout>
    <div class="flex min-h-screen"> 
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md p-4 border-r border-gray-200 flex flex-col">
            <div class="mt-4 flex-grow">
                <h2 class="text-xl font-semibold mb-4">Categories</h2>
                <ul>
                    @foreach($groups as $group)
                        <li class="mb-2">
                            <a href="{{ route('frontpage.category', ['group' => $group['name']]) }}" class="text-teal-600 hover:underline">{{ $group['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white flex flex-col"> 
            <div>
                <nav class="text-teal-600 text-md mb-4" aria-label="Breadcrumb">
                    <ol class="list-reset flex">
                        <li><a href="{{ route('frontpage.index') }}" class="hover:underline">Home</a></li>
                        <li><span class="mx-2">></span></li>
                        <li class="text-black">
                            @if($selected_group)
                            {{ $selected_group['display_name'] }}
                            @endif
                        </li>
                    </ol>
                </nav>
                <div class="pl-4 border-l-4 border-teal-700">
                    @if($selected_group)
                    <h1 class="text-3xl font-semibold mb-4">{{  $selected_group['display_name'] }}</h1>
                    <div class="flex justify-between items-start">
                        <p class="text-gray-600 font-semibold">{{ $selected_group['description'] }}</p>
                    </div>
                    @endif
                </div>
                <hr class="my-4">

            </div>

            <div class="flex-grow overflow-auto">
                @foreach($datasets as $dataset)
                    <div class="mb-4 bg-white shadow rounded-lg p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-3">{{ $dataset['title'] }}</h2>
                        <p class="text-gray-600 mb-5">{{ $dataset['notes'] }}</p>
                        <a href="{{ route('frontpage.data', ['id' => $dataset['id']]) }}" class="text-teal-600 hover:text-teal-800 hover:underline">View Dataset</a>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</x-guest-layout>
