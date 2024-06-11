<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Add Dataset') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Upload your dataset here") }}
        </p>
    </header>

    <form method="post" action="{{ route('publisher.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('post')

        <!-- Dataset Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Dataset Description -->
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required autofocus autocomplete="description" />
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>


         <!-- Tags Description -->
        <div>
            <x-input-label for="tags" :value="__('Tags')" />
            <x-text-input id="tags" class="block mt-1 w-full" type="text" name="tags" :value="old('tags')" required autofocus autocomplete="tags" />
            <x-input-error :messages="$errors->get('tags')" class="mt-2" />
        </div>

        <!-- Groups -->
        <div class="mt-4">
            <x-input-label for="groups" :value="__('Group')" />
            <select id="group" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="group_id" required>
                @foreach ($groups as $id => $display_name)
                    <option value="{{ $id }}">{{ $display_name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('group')" class="mt-2" />
        </div>


        <!-- File Upload -->
        <div>
            <x-input-label for="file" :value="__('Dataset File')" />
            <input id="file" class="block mt-1 w-full rounded-md shadow-sm" type="file" name="file" required />
            <x-input-error :messages="$errors->get('file')" class="mt-2" />
        </div>

        <!-- Checkboxes for Options -->
        <div class="grid grid-cols-3 gap-4 mt-4">
            <div>
                <label for="embeddable" class="flex items-center">
                    <input id="embeddable" type="checkbox" name="is_embedable" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Embeddable') }}</span>
                </label>
            </div>
            <div>
                <label for="enable_api" class="flex items-center">
                    <input id="enable_api" type="checkbox" name="is_api" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Enable API') }}</span>
                </label>
            </div>
            <div>
                <label for="published" class="flex items-center">
                    <input id="published" type="checkbox" name="is_published" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Published') }}</span>
                </label>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
