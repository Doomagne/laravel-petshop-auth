@extends('layouts.app')

@section('title', 'Apply for Adoption')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Adoption Application</h1>
                    <p class="text-gray-600 mt-2">You’re applying to adopt <span class="font-semibold">{{ $dog->name }}</span>.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('dogs.show', ['dog' => $dog->slug]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>←</span><span>Back to Dog</span>
                    </a>
                    <a href="{{ route('applications.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>My Applications</span>
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <form method="POST" action="{{ route('applications.store', ['dog' => $dog->slug]) }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', Auth::user()->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="e.g. 09xx xxx xxxx"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dog</label>
                        <input type="text" value="{{ $dog->name }} ({{ $dog->breed_label }})"
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50" disabled>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              required>{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message (optional)</label>
                    <textarea name="message" rows="5"
                              placeholder="Tell the admin why you want to adopt and your experience with pets."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('message') }}</textarea>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition inline-flex items-center gap-2">
                        <span>Submit Application</span>
                    </button>
                    <a href="{{ route('dogs.show', ['dog' => $dog->slug]) }}"
                       class="bg-white hover:bg-gray-50 text-gray-800 font-semibold py-2 px-6 rounded-lg transition inline-flex items-center gap-2 border border-gray-200">
                        <span>×</span><span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




