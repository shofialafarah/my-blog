<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Postingan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-semibold text-2xl">{{ $post->title }}</h3>
                        <div class="flex-shrink-0">
                            @if (Auth::id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        {{-- Tampilkan Kategori --}}
                        @if ($post->category)
                        <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-2 py-1 rounded-full">{{ $post->category->name }}</span>
                        @endif

                        {{-- Tampilkan Tag --}}
                        @foreach($post->tags as $tag)
                        <span class="text-xs bg-indigo-200 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-200 px-2 py-1 rounded-full">{{ $tag->name }}</span>
                        @endforeach
                    </div>

                    <div class="text-gray-600 dark:text-gray-400 prose mb-6">
                        <p>{{ $post->body }}</p>
                    </div>

                    <small class="text-gray-500 dark:text-gray-400">
                        Diposting oleh:
                        @if ($post->user)
                        <span class="font-semibold">{{ $post->user->name }}</span>
                        @else
                        <span class="italic">Pengguna Tidak Dikenal</span>
                        @endif
                        pada {{ $post->created_at->format('d M Y') }}
                    </small>

                    <div class="flex items-center mt-4">
                        {{-- Tombol Suka --}}
                        @auth
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="mr-4">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-white rounded {{ Auth::user()->likedPosts->contains($post->id) ? 'bg-pink-700' : 'bg-pink-500' }}">
                                @if (Auth::user()->likedPosts->contains($post->id))
                                Tidak Suka
                                @else
                                Suka
                                @endif
                            </button>
                        </form>
                        @endauth
                        <p class="text-gray-600 dark:text-gray-400">{{ $post->likers->count() }} orang menyukai ini.</p>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <h4 class="text-xl font-bold mb-4">Komentar</h4>

                    {{-- Formulir Tambah Komentar --}}
                    @auth
                    <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <textarea name="body" rows="3" class="w-full px-3 py-2 text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Tambahkan komentar..." required></textarea>
                        <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Kirim Komentar
                        </button>
                    </form>
                    @else
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        Anda harus <a href="{{ route('login') }}" class="text-blue-500 hover:underline">masuk</a> untuk berkomentar.
                    </p>
                    @endauth

                    {{-- Tampilkan Daftar Komentar --}}
                    @forelse ($post->comments as $comment)
                    <div class="border border-gray-200 dark:border-gray-700 p-4 mb-4 rounded-lg">
                        <p class="text-gray-800 dark:text-gray-200">{{ $comment->body }}</p>
                        <small class="text-gray-500 dark:text-gray-400">
                            Oleh: <span class="font-semibold">{{ $comment->user->name }}</span>
                            pada {{ $comment->created_at->format('d M Y') }}

                            {{-- Tambahkan tombol hapus di sini --}}
                            @auth
                            @if (Auth::user()->id === $comment->user_id || Auth::user()->id === $comment->post->user_id)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 ml-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');">
                                    Hapus
                                </button>
                            </form>
                            @endif
                            @endauth
                        </small>
                    </div>
                    @empty
                    <p class="text-gray-500 dark:text-gray-400">Belum ada komentar.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>