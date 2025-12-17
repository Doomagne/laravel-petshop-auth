@extends('layouts.app')

@section('title', 'My Applications')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">My Applications</h1>
                    <p class="text-gray-600 mt-2">Track your adoption requests.</p>
                </div>
                <div class="flex flex-wrap gap-3">
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

        @if($applications->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Dog</th>
                                <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Breed</th>
                                <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Status</th>
                                <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Submitted</th>
                                <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($applications as $app)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900">{{ $app->dog?->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $app->dog?->breed_label ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                                            {{ $app->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $app->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $app->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                        ">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $app->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('applications.show', $app) }}" class="text-blue-700 font-semibold hover:underline">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $applications->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-4xl mb-3">📝</div>
                <h2 class="text-xl font-bold text-gray-800">No applications yet</h2>
                <p class="text-gray-600 mt-2">Open a dog profile and click “Apply for Adoption”.</p>
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



