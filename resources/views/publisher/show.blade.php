<x-app-layout>
    <div class="flex h-screen"> 
        <!-- Sidebar -->
        @include('publisher.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white flex flex-col" style="min-height: calc(100vh - 4rem);">
            <h2 class="text-xl font-semibold text-gray-800 mb-2 ml-2 flex justify-between items-center">
                Dataset: {{$dataset['title']}}
            </h2>

            <div class="flex-grow overflow-auto">

            <div class="pl-4 border-l-4 border-teal-700">
                <h1 class="text-3xl font-semibold mb-4">{{ $dataset['title'] }}</h1>
                <div class="flex justify-between items-start">
                    <p class="text-gray-600 font-semibold">{{ $dataset['notes'] }}</p>
                    <div class="flex space-x-2">
                        <span>Categories:</span>
                        @foreach($dataset['groups'] as $group)
                            <a href="{{ route('frontpage.explore', ['group' => $group['name']]) }}" class="text-teal-600 hover:underline">{{ $group['title'] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>



            <hr class="my-4">

            <!-- Resources Section -->
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-2 ml-2">Resources</h2>
                @foreach($dataset['resources'] as $resource)

                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full bg-white shadow rounded-lg">
                        <thead class="bg-teal-50">
                            <tr>

                            <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t  border-b border-teal-600">Name</th>
                            <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t  border-b border-teal-600">Description</th>
                            <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t  border-b border-teal-600">Format</th>
                            <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t  border-b border-teal-600">Size</th>
                            <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t  border-b border-teal-600">Download</th>

                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class=" px-4 py-2">{{ $resource['name'] }}</td>
                                    <td class="px-4 py-2">{{ $resource['description'] }}</td>
                                    <td class="px-4 py-2">{{ $resource['format'] }}</td>
                                    <td class="  px-4 py-2">{{ $resource['size'] }} bytes</td>
                                    <td class=" px-4 py-2">
                                        <a href="{{ $resource['url'] }}" class="text-teal-600 hover:underline">Download</a>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>


                @if(!$local_dataset->is_published)
                <h2 class="text-md font-semibold text-gray-800 mb-1 ml-2 py-4">
                    <span class="text-red-500">DATASET IS NOT PUBLISHED, API & EMBED DISABLED</span>
                </h2>
                @else

                <div class="mb-4">
                <h2 class="text-md font-semibold text-gray-800 mb-1 ml-2 py-4">
                    API : 
                    @if($local_dataset->is_api)
                        <span class="text-teal-500">Enabled</span>
                    @else
                        <span class="text-red-500">Disabled</span>
                    @endif
                </h2>
                    <div class="bg-gray-700 text-white  p-3 font-mono text-xs rounded">
                        {{ $resource['api_url'] }}
                    </div>
                </div>


            @if(!empty($resource['embed_url']))

            <h2 class="text-md font-semibold text-gray-800 mb-1 ml-2 py-4">
                    Embedeble : 
                    @if($local_dataset->is_embedable)
                        <span class="text-teal-500">Enabled</span>
                    @else
                        <span class="text-red-500">Disabled</span>
                    @endif
                </h2>   


                <iframe title="Data viewer" width="100%" height="500" src="{{ $resource['embed_url'] }}" frameborder="0"></iframe>
            @endif

            @endif


            @endforeach

            </div>


            </div>
        </main>
    </div>
</x-app-layout>
