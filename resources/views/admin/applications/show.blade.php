@extends('layouts.app')

@section('title', 'Application')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Application</h1>
                    <p class="text-gray-600 mt-2">
                        Dog: <span class="font-semibold">{{ $application->dog?->name ?? 'N/A' }}</span>
                        • User: <span class="font-semibold">{{ $application->user?->name ?? 'N/A' }}</span>
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.applications.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>←</span><span>Back to Applications</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-800">
                        <span>←</span><span>Admin Dashboard</span>
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
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Applicant Info</h2>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex justify-between gap-4"><span class="font-semibold">Full Name</span><span class="text-right">{{ $application->full_name }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Email</span><span class="text-right">{{ $application->email ?? ($application->user?->email ?? 'N/A') }}</span></div>
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
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Admin Actions</h2>

                    <div class="text-sm text-gray-700 mb-4">
                        Submitted: <span class="font-semibold">{{ $application->created_at->format('M d, Y h:i A') }}</span>
                    </div>

                    <form method="POST" action="{{ route('admin.applications.update', $application) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $application->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Note (optional)</label>
                            <textarea name="admin_note" rows="5"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('admin_note', $application->admin_note) }}</textarea>
                        </div>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition w-full">
                            Save
                        </button>
                    </form>
                </div>

                @if($application->dog)
                    <div class="bg-white rounded-xl shadow-md p-6 mt-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Dog</h3>
                        <div class="text-sm text-gray-700">
                            <div><span class="font-semibold">Name:</span> {{ $application->dog->name }}</div>
                            <div><span class="font-semibold">Breed:</span> {{ $application->dog->breed_label }}</div>
                            <div class="mt-3">
                                <a class="text-blue-700 font-semibold hover:underline" href="{{ route('admin.dogs.show', $application->dog) }}">View in Admin</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection




