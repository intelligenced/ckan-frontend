<x-app-layout>
    <div class="flex h-[86vh]"> 
        <!-- Sidebar -->
        @include('publisher.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white flex flex-col" >
            <h2 class="text-xl font-semibold text-gray-800 mb-2 ml-2 flex justify-between items-center">
                Datasets
                <span class="text-sm font-normal text-gray-600">
                    Dataset Count: 
                    {{ $datasets->count()}}
                </span>
            </h2>

            <div class="flex-grow overflow-auto">
                <table class="min-w-full bg-white shadow rounded-lg">
                    <thead class="bg-teal-50">
                        <tr>
                            <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t border-b border-teal-600">Name</th>
                            <th class="px-6 py-2 text-left text-md font-semibold text-gray-700 border-t border-b border-teal-600">Settings and Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datasets as $dataset)
                        <tr>
                            <td class="px-4 py-2">{{ $dataset->name }}</td>
                            <td class="py-2">
                                <div class="flex items-center space-x-4">
                                    <form action="{{ route('publisher.update', $dataset->id) }}" method="POST" class="flex items-center space-x-4">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <input type="hidden" name="is_api" value="0">
                                            <label>
                                                <input type="checkbox" name="is_api" value="1" {{ $dataset->is_api ? 'checked' : '' }}>
                                                Enable API
                                            </label>
                                        </div>
                                        <div>
                                            <input type="hidden" name="is_embedable" value="0">
                                            <label>
                                                <input type="checkbox" name="is_embedable" value="1" {{ $dataset->is_embedable ? 'checked' : '' }}>
                                                Embedable
                                            </label>
                                        </div>
                                        <div>
                                            <input type="hidden" name="is_published" value="0">
                                            <label>
                                                <input type="checkbox" name="is_published" value="1" {{ $dataset->is_published ? 'checked' : '' }}>
                                                Published
                                            </label>
                                        </div>
                                        <button type="submit" class="text-sm text-white bg-teal-500 hover:bg-teal-700 px-2 py-1 rounded">Update</button>
                                    </form>
                                    <a href="{{ route('publisher.show', $dataset->id) }}" class="text-sm text-white bg-teal-500 hover:bg-teal-700 px-2 py-1 rounded">View</a>
                                    <form action="{{ route('publisher.destroy', $dataset->id ) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-white bg-red-500 hover:bg-red-700 px-2 py-1 rounded" onclick="return confirm('Are you sure you want to delete this dataset?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-app-layout>
