<aside class="w-64 bg-white shadow-md py-4 border-r border-gray-200 flex flex-col">
    <div class="p-4">
        <h2 class="text-xl font-semibold mb-4 mx-2">Search</h2>
        <!-- Search Form -->
        <form action="{{ route('frontpage.explore') }}" method="GET" class="mb-4">
            @if(request('group'))
                <input type="hidden" name="group" value="{{ request('group') }}">
            @endif
            @if(request('tag'))
                <input type="hidden" name="tag" value="{{ request('tag') }}">
            @endif
            <input type="text" name="name" required placeholder="Search by name..." value="{{ request('name') }}" class="w-full px-4 py-2 border border-gray-400 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-600" />
            <button type="submit" class="mt-2 w-full bg-teal-600 text-white py-2 rounded-md hover:bg-teal-700">
                Search
            </button>
        </form>

        <h2 class="text-xl font-semibold mb-4 mx-2">Groups</h2>

        
        <ul>
            @foreach($groups as $group)
                <li class="mb-2">
                    <a href="{{ route('frontpage.explore', ['group' => $group['name']]) }}"
                       class="{{ (isset($selected_group) && $selected_group['name'] == $group['name']) ? 'block px-4 text-teal-600 hover:underline border-l-4 border-teal-600 font-semibold' : 'block px-4 text-teal-600 hover:underline' }}">
                        {{ $group['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>
