@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
    <div class="max-w-lg w-full">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Icon -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-gray-800 mb-2">403</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Access Denied</h2>
            <p class="text-gray-600 mb-8">You don't have permission to access this page.</p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('dashboard') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Go to Dashboard
                </a>
                <button onclick="window.history.back()"
                        class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Go Back
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
