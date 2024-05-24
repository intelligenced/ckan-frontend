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

</script>



@endpush