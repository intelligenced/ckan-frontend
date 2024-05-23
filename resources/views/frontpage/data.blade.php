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
            <h1 class="text-3xl font-semibold mb-4">{{ $dataset['title'] }}</h1>
            <p class="text-gray-600 mb-5">{{ $dataset['notes'] }}</p>

            <!-- Metadata Section -->
            <!-- Compact Metadata Section -->
            <div class="mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Metadata</h2>
                <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white p-4 shadow rounded">
                        <p><strong>Author:</strong> {{ $dataset['author'] ?: 'N/A' }}</p>
                        <p><strong>Maintainer:</strong> {{ $dataset['maintainer'] ?: 'N/A' }}</p>
                        <p><strong>License:</strong> <a href="{{ $dataset['license_url'] }}" class="text-blue-600 hover:underline">{{ $dataset['license_title'] }}</a></p>
                        <p><strong>Created:</strong> {{ \Carbon\Carbon::parse($dataset['metadata_created'])->format('M d, Y') }}</p>
                        <p><strong>Modified:</strong> {{ \Carbon\Carbon::parse($dataset['metadata_modified'])->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-white p-4 shadow rounded">
                        <p><strong>Organization:</strong> {{ $dataset['organization']['title'] }}</p>
                        <p><strong>Owner Org:</strong> {{ $dataset['owner_org'] }}</p>
                        <p><strong>State:</strong> {{ $dataset['state'] }}</p>
                        <p><strong>Version:</strong> {{ $dataset['version'] ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Resources Section -->
            <div class="mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Resources</h2>
                @foreach($dataset['resources'] as $resource)

                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full bg-white shadow rounded-lg">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Name</th>
                                <th class="border px-4 py-2">Description</th>
                                <th class="border px-4 py-2">Format</th>
                                <th class="border px-4 py-2">Size</th>
                                <th class="border px-4 py-2">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="border px-4 py-2">{{ $resource['name'] }}</td>
                                    <td class="border px-4 py-2">{{ $resource['description'] }}</td>
                                    <td class="border px-4 py-2">{{ $resource['format'] }}</td>
                                    <td class="border px-4 py-2">{{ $resource['size'] }} bytes</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ $resource['url'] }}" class="text-blue-600 hover:underline">Download</a>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>

            <h1 class="text-xl font-semibold mb-4">{{ $resource['description'] }}</h1>

            @if(!empty($resource['embed_url']))
                <iframe title="Data viewer" width="880" height="500" src="{{ $resource['embed_url'] }}" frameborder="0"></iframe>
            @else
                N/A
            @endif


            @endforeach

            </div>

                        <!-- Tags Section -->
                        <div class="mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Tags</h2>
                <div class="mt-2">
                    @foreach($dataset['tags'] as $tag)
                        <span class="inline-block bg-gray-200 text-gray-800 text-sm font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $tag['display_name'] }}</span>
                    @endforeach
                </div>
            </div>

        </main>
    </div>
</x-guest-layout>
