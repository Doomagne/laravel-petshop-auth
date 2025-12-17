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

        <!-- Browse Dogs -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Browse Pets</h2>
                    <p class="text-gray-600">See all dog profiles posted by the admin (read-only).</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('dogs.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition inline-flex items-center gap-2">
                        <span>View Dogs</span>
                    </a>
                    <a href="{{ route('cats.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition inline-flex items-center gap-2">
                        <span>View Cats</span>
                    </a>
                    <a href="{{ route('favorites.index') }}"
                       class="bg-white hover:bg-gray-50 text-gray-800 font-semibold py-2 px-6 rounded-lg transition inline-flex items-center gap-2 border border-gray-200">
                        <span>♥</span>
                        <span>My Favorites</span>
                    </a>
                    <a href="{{ route('applications.index') }}"
                       class="bg-white hover:bg-gray-50 text-gray-800 font-semibold py-2 px-6 rounded-lg transition inline-flex items-center gap-2 border border-gray-200">
                        <span>My Applications</span>
                    </a>
                </div>
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
    </div>
</div>
@endsection
