@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Welcome to Doomeng's Pet Shop!</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- To-Do List Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">My To-Do List</h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add Task Form -->
            <div class="mb-6">
                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Task Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            required
                            placeholder="Enter task name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description (Optional)
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="3"
                            placeholder="Enter task description (optional)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        ></textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button 
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition"
                    >
                        Add Task
                    </button>
                </form>
            </div>

            <!-- Tasks List -->
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Your Tasks</h3>
                @if($tasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($tasks as $task)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-800 text-lg mb-1">{{ $task->title }}</h4>
                                        @if($task->description)
                                            <p class="text-sm text-gray-600 mb-2">{{ $task->description }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                            <span>Created: {{ $task->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit"
                                            onclick="return confirm('Are you sure you want to delete this task?')"
                                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-4xl mb-2"></div>
                        <p class="text-gray-600">No tasks yet. Add your first task above!</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Pet Species</h2>
            @php
                $species = \App\Models\Pet::distinct()->pluck('species')->filter();
                $selectedSpecies = request('species');
            @endphp
            @if($species->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($species as $specie)
                        <a href="{{ route('dashboard', ['species' => $specie]) }}"
                           class="border-2 {{ $selectedSpecies == $specie ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition text-center">
                            <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-600">{{ $specie }}</h3>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">No pet species available yet.</p>
            @endif
        </div>

        @php
            $selectedSpecies = request('species');
        @endphp
        @if($selectedSpecies)
            @php
                $pets = \App\Models\Pet::where('species', $selectedSpecies)->where('is_available', true)->get();
            @endphp
            @if($pets->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Pets - {{ $selectedSpecies }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pets as $pet)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition">
                                <p class="font-medium text-gray-800 text-lg mb-2">{{ $pet->name }}</p>
                                @if($pet->description)
                                    <p class="text-sm text-gray-600 mb-2">{{ $pet->description }}</p>
                                @endif
                                <p class="text-sm text-gray-600 mb-1"><strong>Breed:</strong> {{ $pet->breed }}</p>
                                <p class="text-sm text-gray-600 mb-1"><strong>Age:</strong> {{ $pet->age }} years old</p>
                                <p class="text-lg font-semibold text-blue-600 mb-1">₱{{ number_format($pet->price, 2) }}</p>
                                <p class="text-xs text-gray-500">Status: {{ $pet->is_available ? 'Available' : 'Not Available' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-gray-600">No pets in this species yet.</p>
            @endif
        @else
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-600 text-center">Click on a pet species above to view pets.</p>
            </div>
        @endif
    </div>
</div>
@endsection
