<x-guest-layout>
    <div class="flex">
        <!-- Sidebar -->
        @include('frontpage.partials.sidebar', ['groups' => $groups, 'selected_group' => $selected_group ?? null])


        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white">

        <nav class="text-teal-600  text-md mb-4" aria-label="Breadcrumb">
                <ol class="list-reset flex">
                    <li><a href="{{ route('frontpage.index') }}" class="hover:underline">Home</a></li>
                    <li><span class="mx-2">></span></li>
                    <li><a href="{{ route('frontpage.explore') }}" class="hover:underline">Explore</a></li>
                    <li><span class="mx-2">></span></li>
                    <li class="text-black">{{ $dataset['title'] }}</li>
                </ol>
            </nav>


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

                <div class="mb-4">
                    <h2 class="text-md font-semibold text-gray-800 mb-1 ml-2">API</h2>
                    <div class="bg-gray-700 text-white  p-3 font-mono text-xs rounded">
                        {{ $resource['api_url'] }}
                    </div>
                </div>


            @if(!empty($resource['embed_url']))
                <iframe title="Data viewer" width="100%" height="500" src="{{ $resource['embed_url'] }}" frameborder="0"></iframe>
            @endif


            @endforeach

            </div>

            <hr class="mt-6">


            <!-- Metadata Section -->
            <!-- Compact Metadata Section -->
            <div class="bg-gray-50 p-4">
                <!-- Metadata and Tags Section -->
                <div class="flex flex-col lg:flex-row mb-4">
                    <!-- Metadata Section -->
                    <div class="lg:w-2/3 mr-0 lg:mr-4 mb-4 lg:mb-0">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Metadata</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                            <div class="bg-white p-4 shadow rounded">
                                <p><strong>Author:</strong> {{ data_get($dataset, 'author', 'N/A') }}</p>
                                <p><strong>Maintainer:</strong> {{ data_get($dataset, 'maintainer', 'N/A') }}</p>
                                <p><strong>License:</strong> <a href="{{ data_get($dataset, 'license_url', '#') }}" class="text-teal-600 hover:underline">{{ data_get($dataset, 'license_title', 'N/A') }}</a></p>
                                <p><strong>Created:</strong> {{ \Carbon\Carbon::parse(data_get($dataset, 'metadata_created', now()))->format('M d, Y') }}</p>
                                <p><strong>Modified:</strong> {{ \Carbon\Carbon::parse(data_get($dataset, 'metadata_modified', now()))->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-white p-4 shadow rounded">
                                <p><strong>Organization:</strong> {{ data_get($dataset, 'organization.title', 'N/A') }}</p>
                                <p><strong>Owner Org:</strong> {{ data_get($dataset, 'owner_org', 'N/A') }}</p>
                                <p><strong>State:</strong> {{ data_get($dataset, 'state', 'N/A') }}</p>
                                <p><strong>Version:</strong> {{ data_get($dataset, 'version', 'N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tags Section -->
                    <div class="lg:w-1/3">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Tags</h2>
                        <div class="flex flex-wrap">
                            @foreach($dataset['tags'] as $tag)
                                <span class="inline-block bg-gray-200 text-gray-800 text-sm font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">{{ $tag['display_name'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


        </main>
    </div>
</x-guest-layout>
