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
            @if(request('organization'))
                <input type="hidden" name="organization" value="{{ request('organization') }}">
            @endif
            <input type="text" name="name" required placeholder="Search by name..." value="{{ request('name') }}" class="w-full px-4 py-2 border border-gray-400 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-600" />
            <button type="submit" class="mt-2 w-full bg-teal-600 text-white py-2 rounded-md hover:bg-teal-700">
                Search
            </button>
        </form>
        <div>
        <h2 class="text-xl font-semibold mb-4 mx-2">Groups</h2>
        <ul>
            @foreach($groups as $group)
                <li class="mb-2">
                    <a href="{{ route('frontpage.explore', array_filter([
                            'group' => $group['name'], 
                            'tag' => request('tag'), 
                            'organization' => request('organization'),
                            'name' => request('name')
                        ])) }}"
                    class="{{ (isset($selected_group) && $selected_group['name'] == $group['name']) ? 'block px-4 text-teal-600 hover:underline border-l-4 border-teal-600 font-semibold' : 'block px-4 text-teal-600 hover:underline' }}">
                        {{ $group['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
        </div>
        <div class="mb-4">
        <h2 class="text-xl font-semibold mb-4 mx-2">Organisations</h2>
        <select onchange="handleOrganizationChange(this)">
            @if(request('organization'))
                <option value="">Clear Selection</option>
            @endif
            <option disabled {{ request('organization') ? '' : 'selected' }}>Select an Organization</option>
            
            @foreach($organisations as $organisation)
                <option value="{{ $organisation['name'] }}" 
                        {{ (request('organization') == $organisation['name']) ? 'selected' : '' }}>
                    {{ $organisation['title'] }}
                </option>
            @endforeach
        </select>
        <div>

        <div class="mt-4">

        <h2 class="text-xl font-semibold mb-2 mx-2">Tags</h2>
        <div class="flex flex-wrap mb-2">
            @foreach($tags as $tag)
                <div class="inline-flex items-center {{ (request('tag') == $tag['name']) ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-800' }} rounded text-sm m-1">
                    <a href="{{ route('frontpage.explore', array_filter([
                            'tag' => $tag['name'], 
                            'group' => request('group'), 
                            'organization' => request('organization'),
                            'name' => request('name')
                        ])) }}"
                    class="px-2 py-1 rounded">
                        {{ $tag['name'] }}
                    </a>
                    @if(request('tag') == $tag['name'])
                        <button onclick="removeTagFilter()" class="text-white text-lg px-2 ">
                            &times;
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
                    </div>

    </div>
</aside>


@push('scripts')
<script>
function handleOrganizationChange(select) {
    const basePath = "{{ url('explore') }}";
    const searchParams = new URLSearchParams(window.location.search);
    const selectedOrganization = select.value;

    if (selectedOrganization) {
        searchParams.set('organization', selectedOrganization);
    } else {
        searchParams.delete('organization');
    }

    if ('{{ request('group') }}') {
        searchParams.set('group', '{{ request('group') }}');
    } else {
        searchParams.delete('group');
    }

    if ('{{ request('tag') }}') {
        searchParams.set('tag', '{{ request('tag') }}');
    } else {
        searchParams.delete('tag');
    }

    if ('{{ request('name') }}') {
        searchParams.set('name', '{{ request('name') }}');
    } else {
        searchParams.delete('name');
    }
    const newUrl = `${basePath}?${searchParams.toString()}`;
    window.location = newUrl;
}

function removeTagFilter() {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.delete('tag');
    window.location = currentUrl.toString();
}

</script>



@endpush