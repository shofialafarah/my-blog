<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Postingan Baru') }}
        </h2>
    </x-slot>

    <!-- Add Choices.js CSS -->
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css" rel="stylesheet">
    <style>
        /* Custom styles for Choices.js dark mode */
        .dark .choices {
            background-color: #374151 !important;
            border-color: #4B5563 !important;
        }
        
        .dark .choices__inner {
            background-color: #374151 !important;
            border-color: #4B5563 !important;
            color: #E5E7EB !important;
        }
        
        .dark .choices__list--dropdown {
            background-color: #374151 !important;
            border-color: #4B5563 !important;
        }
        
        .dark .choices__item {
            color: #E5E7EB !important;
        }
        
        .dark .choices__item--selectable {
            color: #E5E7EB !important;
        }
        
        .dark .choices__item--selectable:hover {
            background-color: #4B5563 !important;
        }
        
        .dark .choices__item--choice {
            color: #E5E7EB !important;
        }
        
        .dark .choices__item--choice.is-highlighted {
            background-color: #3B82F6 !important;
        }
        
        .dark .choices__placeholder {
            color: #9CA3AF !important;
        }
        
        .dark .choices__input {
            background-color: transparent !important;
            color: #E5E7EB !important;
        }
        
        .dark .choices__input:focus {
            color: #E5E7EB !important;
        }

        /* Style for selected tags */
        .choices__item--selected {
            background-color: #3B82F6 !important;
            border-color: #2563EB !important;
            color: white !important;
        }
        
        .choices__button {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-left-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important;
        }
        
        .choices__button:hover {
            background-color: rgba(255, 255, 255, 0.3) !important;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                Judul <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" 
                                   class="w-full px-3 py-2 text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 @error('title') border-red-500 @enderror" 
                                   placeholder="Masukkan judul postingan..." required>
                            @error('title')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" id="category_id" 
                                    class="w-full px-3 py-2 text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 @error('category_id') border-red-500 @enderror" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="body" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                Isi Postingan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="body" name="body" rows="6" 
                                      class="w-full px-3 py-2 text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 @error('body') border-red-500 @enderror" 
                                      placeholder="Tulis isi postingan Anda di sini..." required>{{ old('body') }}</textarea>
                            @error('body')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                Tag (Opsional)
                            </label>
                            <select name="tags[]" id="tags" multiple 
                                    class="w-full px-3 py-2 text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border rounded-lg @error('tags') border-red-500 @enderror">
                                @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" 
                                        @if(is_array(old('tags')) && in_array($tag->id, old('tags'))) selected @endif>
                                    {{ $tag->name }}
                                </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                💡 Ketik untuk mencari tag yang sudah ada atau tekan Enter untuk menambah tag baru
                            </p>
                            @error('tags')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Postingan
                            </button>
                            <a href="{{ route('posts.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Choices.js JavaScript -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices.js for tags with search and add new functionality
            const tagsElement = document.getElementById('tags');
            
            if (tagsElement) {
                const choices = new Choices(tagsElement, {
                    removeItemButton: true,
                    searchEnabled: true,
                    searchPlaceholderValue: 'Ketik untuk mencari tag...',
                    noResultsText: 'Tidak ada tag ditemukan',
                    noChoicesText: 'Ketik untuk menambah tag baru',
                    itemSelectText: 'Tekan untuk memilih',
                    addItemText: (value) => {
                        return `Tekan Enter untuk menambah "<b>${value}</b>"`;
                    },
                    maxItemText: (maxItemCount) => {
                        return `Maksimal ${maxItemCount} tag dapat dipilih`;
                    },
                    shouldSort: false,
                    searchResultLimit: 10,
                    placeholder: true,
                    placeholderValue: 'Pilih atau ketik tag baru...',
                    // Allow adding new items
                    editItems: true,
                    addItems: true,
                    duplicateItemsAllowed: false,
                    paste: false,
                    // Custom class names
                    classNames: {
                        containerOuter: 'choices',
                        containerInner: 'choices__inner',
                        input: 'choices__input',
                        inputCloned: 'choices__input--cloned',
                        list: 'choices__list',
                        listItems: 'choices__list--multiple',
                        listSingle: 'choices__list--single',
                        listDropdown: 'choices__list--dropdown',
                        item: 'choices__item',
                        itemSelectable: 'choices__item--selectable',
                        itemDisabled: 'choices__item--disabled',
                        itemChoice: 'choices__item--choice',
                        placeholder: 'choices__placeholder',
                        group: 'choices__group',
                        groupHeading: 'choices__heading',
                        button: 'choices__button',
                        activeState: 'is-active',
                        focusState: 'is-focused',
                        openState: 'is-open',
                        disabledState: 'is-disabled',
                        highlightedState: 'is-highlighted',
                        selectedState: 'is-selected',
                        flippedState: 'is-flipped',
                        loadingState: 'is-loading',
                        noResults: 'has-no-results',
                        noChoices: 'has-no-choices'
                    }
                });

                // Handle adding new tags dynamically
                tagsElement.addEventListener('addItem', function(event) {
                    console.log('Tag ditambahkan:', event.detail.value);
                    
                    // Optional: Send AJAX request to create new tag in database
                    if (event.detail.customProperties && event.detail.customProperties.isNew) {
                        // This would be for creating new tags on-the-fly
                        // You can implement this feature later if needed
                    }
                });

                // Handle removing tags
                tagsElement.addEventListener('removeItem', function(event) {
                    console.log('Tag dihapus:', event.detail.value);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>