@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Application Details</h1>
                    <p class="text-gray-600 mt-2">
                        Dog: <span class="font-semibold">{{ $application->dog?->name ?? 'N/A' }}</span>
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('applications.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>←</span><span>My Applications</span>
                    </a>
                    @if($application->dog)
                        <a href="{{ route('dogs.show', ['dog' => $application->dog->slug]) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                            <span>🐶</span><span>View Dog</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Your Info</h2>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex justify-between gap-4"><span class="font-semibold">Full Name</span><span class="text-right">{{ $application->full_name }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Email</span><span class="text-right">{{ $application->email ?? 'N/A' }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Phone</span><span class="text-right">{{ $application->phone }}</span></div>
                        <div class="pt-2">
                            <div class="font-semibold mb-1">Address</div>
                            <div class="text-gray-700 whitespace-pre-line">{{ $application->address }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 mt-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">Message</h2>
                    @if($application->message)
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $application->message }}</p>
                    @else
                        <p class="text-gray-600">No message.</p>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">Status</h2>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                    <div class="mt-3 text-sm text-gray-700">
                        Submitted: <span class="font-semibold">{{ $application->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                @if($application->admin_note)
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mt-6">
                        <h3 class="font-bold text-blue-900">Admin Note</h3>
                        <p class="text-blue-900/80 text-sm mt-1 whitespace-pre-line">{{ $application->admin_note }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection




