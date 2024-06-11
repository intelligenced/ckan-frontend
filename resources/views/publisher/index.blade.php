<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Datasets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="overflow-x-auto mb-4">
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
                                    <a href="{{ route('publisher.update', $dataset->id) }} " class="text-sm text-white bg-teal-500 hover:bg-teal-700 px-2 py-1 rounded">View</a>
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

            </div>
        </div>
    </div>
</x-app-layout>
