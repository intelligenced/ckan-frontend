<x-app-layout>
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('publisher.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white flex flex-col">
            <h2 class="text-xl font-semibold text-gray-800 mb-2 ml-2 flex justify-between items-center">
                Publish Your Dataset
            </h2>

            <div class="flex-grow overflow-auto">
            @include('publisher.partials.add-dataset-form')
            </div>
        </main>
    </div>
</x-app-layout>
