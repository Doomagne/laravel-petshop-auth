@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">My Favorites</h1>
                    <p class="text-gray-600 mt-2">Dogs you saved for later.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('dogs.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                       <span>←</span><span>Browse Dogs</span>
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>←</span><span>Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $favorite)
                    @php($dog = $favorite->dog)
                    @if($dog)
                        <div class="group bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition">
                            <div class="relative h-44 bg-gray-200 overflow-hidden">
                                <a href="{{ route('dogs.show', ['dog' => $dog->slug]) }}" class="block w-full h-full">
                                    @if($dog->main_image_url)
                                        <img src="{{ $dog->main_image_url }}"
                                             alt="{{ $dog->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                                            No photo
                                        </div>
                                    @endif
                                </a>

                                <div class="absolute top-3 right-3">
                                    <form method="POST" action="{{ route('favorites.destroy', ['dog' => $dog->slug]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-10 h-10 rounded-full bg-white/90 hover:bg-white text-red-600 shadow flex items-center justify-center transition"
                                                title="Remove from favorites">
                                            ♥
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-700 transition">
                                            {{ $dog->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-0.5">{{ $dog->breed_label }}</p>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                                        {{ $dog->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $dog->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $dog->status === 'fostered' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $dog->status === 'adopted' ? 'bg-gray-200 text-gray-800' : '' }}
                                    ">
                                        {{ ucfirst($dog->status) }}
                                    </span>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                                    <div class="text-gray-700"><span class="font-semibold">Age:</span> {{ $dog->age_label }}</div>
                                    <div class="text-gray-700"><span class="font-semibold">Gender:</span> {{ ucfirst($dog->gender ?? 'unknown') }}</div>
                                </div>

                                <div class="mt-4 text-sm font-semibold text-blue-700">
                                    <a href="{{ route('dogs.show', ['dog' => $dog->slug]) }}" class="hover:underline">
                                        View details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-6">
                {{ $favorites->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-4xl mb-3">♥</div>
                <h2 class="text-xl font-bold text-gray-800">No favorites yet</h2>
                <p class="text-gray-600 mt-2">Browse dogs and tap the heart to save them here.</p>
                <div class="mt-4">
                    <a href="{{ route('dogs.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition inline-flex items-center gap-2">
                        <span>🐶</span><span>Browse Dogs</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection



