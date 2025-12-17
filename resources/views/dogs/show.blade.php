@extends('layouts.app')

@section('title', $dog->name)

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $dog->name }}</h1>
                    <p class="text-gray-600 mt-2">{{ $dog->breed_label }} • {{ $dog->age_label }} • {{ ucfirst($dog->gender ?? 'unknown') }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if($existingApplication)
                        <a href="{{ route('applications.show', $existingApplication) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                            <span>📝</span><span>View My Application</span>
                        </a>
                    @else
                        @if($dog->status === 'adopted')
                            <button type="button" disabled
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 text-gray-600 font-semibold cursor-not-allowed">
                                <span>📝</span><span>Adoption Closed</span>
                            </button>
                        @else
                            <a href="{{ route('applications.create', ['dog' => $dog->slug]) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold transition">
                                <span>📝</span><span>Apply for Adoption</span>
                            </a>
                        @endif
                    @endif

                    @if($isFavorited)
                        <form method="POST" action="{{ route('favorites.destroy', ['dog' => $dog->slug]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                                <span>♥</span><span>Favorited</span>
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('favorites.store', ['dog' => $dog->slug]) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 font-semibold transition">
                                <span>♡</span><span>Add to Favorites</span>
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('favorites.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>♥</span><span>My Favorites</span>
                    </a>
                    <a href="{{ route('dogs.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>←</span><span>Back to Dogs</span>
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

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="bg-gray-200">
                        @if($dog->main_image_url)
                            <img src="{{ $dog->main_image_url }}" alt="{{ $dog->name }}" class="w-full h-80 object-cover">
                        @else
                            <div class="w-full h-80 flex items-center justify-center text-gray-500">No photo</div>
                        @endif
                    </div>

                    @if(count($dog->gallery_urls) > 0)
                        <div class="p-4 border-t border-gray-100">
                            <h2 class="text-lg font-bold text-gray-800 mb-3">More photos</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                @foreach($dog->gallery_urls as $url)
                                    <a href="{{ $url }}" target="_blank" class="block bg-gray-100 rounded-lg overflow-hidden">
                                        <img src="{{ $url }}" alt="Gallery photo" class="w-full h-24 object-cover hover:opacity-90 transition">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 mt-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">About {{ $dog->name }}</h2>
                    @if($dog->description)
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $dog->description }}</p>
                    @else
                        <p class="text-gray-600">No description yet.</p>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">Details</h2>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            {{ $dog->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $dog->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $dog->status === 'fostered' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $dog->status === 'adopted' ? 'bg-gray-200 text-gray-800' : '' }}
                        ">
                            {{ ucfirst($dog->status) }}
                        </span>
                    </div>

                    <div class="mt-4 space-y-3 text-sm text-gray-700">
                        <div class="flex justify-between gap-4">
                            <span class="font-semibold">Breed</span>
                            <span class="text-right">{{ $dog->breed_label }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="font-semibold">Age</span>
                            <span class="text-right">{{ $dog->age_label }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="font-semibold">Gender</span>
                            <span class="text-right">{{ ucfirst($dog->gender ?? 'unknown') }}</span>
                        </div>
                        @if($dog->color)
                            <div class="flex justify-between gap-4">
                                <span class="font-semibold">Color</span>
                                <span class="text-right">{{ $dog->color }}</span>
                            </div>
                        @endif
                        @if($dog->size)
                            <div class="flex justify-between gap-4">
                                <span class="font-semibold">Size</span>
                                <span class="text-right">{{ $dog->size }}</span>
                            </div>
                        @endif
                        @if($dog->location)
                            <div class="flex justify-between gap-4">
                                <span class="font-semibold">Location</span>
                                <span class="text-right">{{ $dog->location }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between gap-4">
                            <span class="font-semibold">Vaccinated</span>
                            <span class="text-right">{{ $dog->vaccinated ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="font-semibold">Sterilized</span>
                            <span class="text-right">{{ $dog->sterilized ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mt-6">
                    <h3 class="font-bold text-blue-900">User note</h3>
                    <p class="text-blue-900/80 text-sm mt-1">
                        This is a read-only post. Users can view dog profiles, but cannot edit or delete them.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


