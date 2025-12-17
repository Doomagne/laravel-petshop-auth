@extends('layouts.app')

@section('title', 'Adoption Applications')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Adoption Applications</h1>
                    <p class="text-gray-600 mt-2">Review and update adoption requests submitted by users.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>←</span><span>Admin Dashboard</span>
                    </a>
                    <a href="{{ route('admin.dogs.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>🐶</span><span>Manage Dogs</span>
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
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-bold text-gray-800">Filter</h2>
                @if($status)
                    <a href="{{ route('admin.applications.index') }}" class="text-sm font-semibold text-blue-700 hover:underline">Clear</a>
                @endif
            </div>
            <div class="flex flex-wrap gap-2 mt-3">
                <a href="{{ route('admin.applications.index') }}"
                   class="px-4 py-2 rounded-full border text-sm font-semibold transition {{ empty($status) ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-800 hover:bg-gray-50' }}">
                    All
                </a>
                <a href="{{ route('admin.applications.index', ['status' => 'pending']) }}"
                   class="px-4 py-2 rounded-full border text-sm font-semibold transition {{ $status === 'pending' ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-800 hover:bg-gray-50' }}">
                    Pending
                </a>
                <a href="{{ route('admin.applications.index', ['status' => 'approved']) }}"
                   class="px-4 py-2 rounded-full border text-sm font-semibold transition {{ $status === 'approved' ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-800 hover:bg-gray-50' }}">
                    Approved
                </a>
                <a href="{{ route('admin.applications.index', ['status' => 'rejected']) }}"
                   class="px-4 py-2 rounded-full border text-sm font-semibold transition {{ $status === 'rejected' ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-800 hover:bg-gray-50' }}">
                    Rejected
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">User</th>
                            <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Dog</th>
                            <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Status</th>
                            <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Submitted</th>
                            <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($applications as $app)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900">{{ $app->user?->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-600">{{ $app->email ?? $app->user?->email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900">{{ $app->dog?->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-600">{{ $app->dog?->breed_label ?? 'N/A' }}</div>
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
                                    <a href="{{ route('admin.applications.show', $app) }}" class="text-blue-700 font-semibold hover:underline">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-gray-600">No applications yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection




