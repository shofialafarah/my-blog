<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Daftar Postingan</h3>
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            <i class="fas fa-plus mr-2"></i> Buat Postingan Baru
                        </a>
                    </div>

                    {{-- Formulir Pencarian --}}
                    <form action="{{ route('posts.index') }}" method="GET" class="mb-6">
                        <div class="flex items-center">
                            <input type="text" name="search" placeholder="Cari postingan..." value="{{ request('search') }}" class="flex-grow rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="submit" class="ml-2 px-4 py-2 bg-gray-600 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                Cari
                            </button>
                        </div>
                    </form>

                    @foreach ($posts as $post)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h4 class="text-xl font-bold mb-1">
                            <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                                {{ $post->title }}
                            </a>
                        </h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">{{ Str::limit($post->body, 150) }}</p>
                        <small class="text-gray-500 dark:text-gray-400">
                            Diposting oleh:
                            @if ($post->user)
                            <span class="font-semibold">{{ $post->user->name }}</span>
                            @else
                            <span class="italic">Pengguna Tidak Dikenal</span>
                            @endif
                            pada {{ $post->created_at->format('d M Y') }}
                        </small>

                        {{-- Di dalam loop @foreach ($posts as $post) --}}
                        <div class="flex items-center space-x-2 mt-2">
                            @if (Auth::id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white font-semibold text-xs uppercase tracking-widest rounded-md transition ease-in-out duration-150 transform hover:scale-105">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white font-semibold text-xs uppercase tracking-widest rounded-md transition ease-in-out duration-150 transform hover:scale-105">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>