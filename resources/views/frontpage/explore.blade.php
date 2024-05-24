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
                        <p class="text-gray-600">{{ $selected_group['description'] }}</p>
                    </div>
                    @endif
                </div>
                <hr class="my-4">

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
    <table class="min-w-full bg-white shadow rounded-md">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-md font-semibold text-gray-700 border-b-2 border-gray-300">Title</th>
                <th class="px-6 py-3 text-left text-md font-semibold text-gray-700 border-b-2 border-gray-300">Notes</th>
                <th class="px-6 py-3 text-left text-md font-semibold text-gray-700 border-b-2 border-gray-300">Organisation</th>
                <th class="px-6 py-3 text-left text-md font-semibold text-gray-700 border-b-2 border-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-300">
            @foreach($datasets as $dataset)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 whitespace-nowrap text-md text-gray-900 ">{{ $dataset['title'] }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-md text-gray-600">{{ $dataset['notes'] }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-md text-gray-600">
                        {{ $dataset['organization']['title'] }}
                    </td>

                    <td class="px-6 py-3 whitespace-nowrap text-md font-medium">
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
