<aside class="w-64 bg-white shadow-md py-4 border-r border-gray-200 flex flex-col">
    <div class="flex-grow p-4">
        <h2 class="text-xl font-semibold mb-4 mx-2">Categories</h2>
        <ul>
            @foreach($groups as $group)
                <li class="mb-2">
                    <a href="{{ route('frontpage.explore', ['group' => $group['name']]) }}"
                       class="{{ (isset($selected_group) && $selected_group['name'] == $group['name']) ? 'block px-4 text-teal-600 hover:underline border-l-4 border-teal-600 font-semibold' : 'block px-4  text-teal-600 hover:underline' }}">
                        {{ $group['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>
