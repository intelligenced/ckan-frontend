<!-- Sidebar -->
<aside class="w-64 bg-white shadow-md py-4 border-r border-gray-200 flex flex-col">
    <div class="p-4">
    <h2 class="text-xl font-semibold mb-4 mx-2">Dashboard</h2>
        <ul>
            <li class="mb-2">
                <a href="{{ route('publisher.create') }}"
                class="block px-4 text-teal-600 hover:underline  font-semibold">
                    Publish Datasets
                </a>
            </li>

            <li class="mb-2">
                <a href="{{ route('publisher.index') }}"
                class="block px-4 text-teal-600 hover:underline font-semibold">
                    Datasets
                </a>
            </li>
        </ul>
    </div>
</aside>