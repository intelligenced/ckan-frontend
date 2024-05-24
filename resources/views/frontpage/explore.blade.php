<x-guest-layout>
    <div class="flex min-h-screen"> 
        <!-- Sidebar -->
        @include('frontpage.partials.sidebar', ['groups' => $groups, 'selected_group' => $selected_group ?? null])

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white flex flex-col"> 
            <div>
                <nav class="text-teal-600 text-md mb-4" aria-label="Breadcrumb">
                    <ol class="list-reset flex">
                        <li><a href="{{ route('frontpage.index') }}" class="hover:underline">Home</a></li>
                        <li><span class="mx-2">></span></li>
                        <li><a href="{{ route('frontpage.explore') }}" class="hover:underline">Explore</a></li>
                        @if($selected_group)
                        <li><span class="mx-2">></span></li>
                        <li class="text-black">
                            {{ $selected_group['display_name'] }}
                        </li>
                        @endif

                    </ol>
                </nav>
                <div class="pl-4 border-l-4 border-teal-600">
                    @if($selected_group)
                    <h1 class="text-3xl font-semibold mb-4">{{  $selected_group['display_name'] }}</h1>
                    <div class="flex justify-between items-start">
                        <p class="text-gray-600">{{ $selected_group['description'] }}</p>
                    </div>
                    @else
                    <h1 class="text-3xl font-semibold mb-4">Explore</h1>
                    <div class="flex justify-between items-start">
                        <p class="text-gray-600">
                            Access comprehensive datasets from the Government of Maldives. Engage with critical data insights to understand national trends, policies, and administrative outcomes.
                        </p>
                    </div>
                    @endif
                </div>
                <hr class="my-4">
            </div>

            <div class=" px-4 py-2 mb-2 bg-teal-50 border-teal-600 border">
    Filters:
    @if(request('group'))
        <span class="bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2">
            Group: {{ request('group') }}
            <a href="{{ request()->fullUrlWithQuery(['group' => null]) }}" class="text-white font-bold ml-1">×</a>
        </span>
    @endif
    @if(request('tag'))
        <span class="bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2">
            Tag: {{ request('tag') }}
            <a href="{{ request()->fullUrlWithQuery(['tag' => null]) }}" class="text-white font-bold ml-1">×</a>
        </span>
    @endif
    @if(request('name'))
        <span class="bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2">
            Query: {{ request('name') }}
            <a href="{{ request()->fullUrlWithQuery(['name' => null]) }}" class="text-white font-bold ml-1">×</a>
        </span>
    @endif
</div>

 
            <h2 class="text-xl font-semibold text-gray-800 mb-2 ml-2 flex justify-between items-center">
                Datasets
                <span class="text-sm font-normal text-gray-600">
                    Dataset Count: 
                    @if(is_array($datasets) && count($datasets) > 0)
                        {{ count($datasets) }}
                    @else
                        0
                    @endif
                </span>
            </h2>

            <div class="flex-grow overflow-auto">
    <table class="min-w-full shadow-sm rounded-lg">
        <thead class="bg-teal-50">
            <tr>
                <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t border-b border-teal-600">Title</th>
                <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t  border-b border-teal-600">Notes</th>
                <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t  border-b border-teal-600">Organisation</th>
                <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t  border-b border-teal-600">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-teal-300">
            @foreach($datasets as $dataset)
                <tr class="hover:bg-teal-50">
                    <td class="px-6 py-2 whitespace-nowrap text-md text-gray-900">{{ $dataset['title'] }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-md text-gray-600">{{ $dataset['notes'] }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-md text-gray-600">
                        {{ $dataset['organization']['title'] }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-md font-medium">
                        <a href="{{ route('frontpage.data', ['id' => $dataset['id']]) }}" class="text-teal-600 hover:text-teal-800 hover:underline">View Dataset</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>




        </main>
    </div>
</x-guest-layout>
