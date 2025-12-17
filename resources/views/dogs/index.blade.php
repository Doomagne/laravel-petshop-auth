@extends('layouts.app')

@section('title', 'Dogs')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Meet Our Dogs</h1>
                    <p class="text-gray-600 mt-2">Browse all dog profiles posted by the admin. Choose a breed to filter.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('favorites.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>♥</span><span>My Favorites</span>
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>←</span><span>Back to Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between gap-4 mb-3">
                <h2 class="text-xl font-bold text-gray-800">Breeds</h2>
                @if($breedId)
                    <a href="{{ route('dogs.index') }}"
                       class="text-sm font-semibold text-blue-700 hover:underline">
                        Clear filter
                    </a>
                @endif
            </div>

            <div class="flex gap-2 overflow-x-auto pb-2" style="-webkit-overflow-scrolling: touch;">
                <a href="{{ route('dogs.index') }}"
                   class="shrink-0 px-4 py-2 rounded-full border text-sm font-semibold transition
                          {{ empty($breedId) ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-800 hover:bg-gray-50' }}">
                    All
                </a>

                @forelse($breeds as $breed)
                    <a href="{{ route('dogs.index', ['breed' => $breed->id]) }}"
                       class="shrink-0 px-4 py-2 rounded-full border text-sm font-semibold transition
                              {{ (int)$breedId === (int)$breed->id ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-800 hover:bg-gray-50' }}">
                        {{ $breed->name }}
                    </a>
                @empty
                    <div class="text-sm text-gray-600">No breeds yet.</div>
                @endforelse
            </div>
        </div>

        @if($dogs->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($dogs as $dog)
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
                                @if(in_array($dog->id, $favoritedDogIds))
                                    <form method="POST" action="{{ route('favorites.dogs.destroy', ['dog' => $dog->slug]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-10 h-10 rounded-full bg-white/90 hover:bg-white text-red-600 shadow flex items-center justify-center transition"
                                                title="Remove from favorites">
                                            ♥
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('favorites.dogs.store', ['dog' => $dog->slug]) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-10 h-10 rounded-full bg-white/90 hover:bg-white text-gray-800 shadow flex items-center justify-center transition"
                                                title="Add to favorites">
                                            ♡
                                        </button>
                                    </form>
                                @endif
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

                            <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                <span class="px-2 py-1 rounded-full {{ $dog->vaccinated ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $dog->vaccinated ? 'Vaccinated' : 'Not vaccinated' }}
                                </span>
                                <span class="px-2 py-1 rounded-full {{ $dog->sterilized ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $dog->sterilized ? 'Sterilized' : 'Not sterilized' }}
                                </span>
                            </div>

                            <div class="mt-4 text-sm font-semibold text-blue-700">
                                <a href="{{ route('dogs.show', ['dog' => $dog->slug]) }}" class="hover:underline">
                                    View details →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $dogs->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-4xl mb-3">:C</div>
                <h2 class="text-xl font-bold text-gray-800">No dogs found</h2>
                <p class="text-gray-600 mt-2">Try choosing a different breed.</p>
            </div>
        @endif
    </div>
</div>
@endsection


