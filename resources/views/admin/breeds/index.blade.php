@extends('layouts.app')

@section('title', 'Manage Breeds')

@section('content')
<div style="max-width: 1100px; margin: 2rem auto; padding: 1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h1 style="font-size:1.6rem; font-weight:700; color:#111827;">Breed Management</h1>
        <a href="{{ route('admin.dashboard') }}" style="color:#2563eb; text-decoration:underline;">← Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div style="background:#ecfdf3; border:1px solid #bbf7d0; color:#166534; padding:.75rem 1rem; border-radius:8px; margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecdd3; color:#991b1b; padding:.75rem 1rem; border-radius:8px; margin-bottom:1rem;">
            <ul style="margin:0; padding-left:1rem; list-style:disc;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08); padding:1rem 1.25rem; margin-bottom:1.5rem;">
        <h2 style="font-size:1.1rem; margin:0 0 .75rem 0;">Add Breed</h2>
        <form action="{{ route('admin.breeds.store') }}" method="POST" style="display:grid; grid-template-columns:2fr 1fr 2fr auto; gap:.75rem; align-items:end;">
            @csrf
            <div>
                <label style="display:block; font-weight:600; margin-bottom:.25rem;">Name</label>
                <input name="name" required class="form-control" style="width:100%; padding:.6rem; border-radius:8px; border:1px solid #d1d5db;" placeholder="e.g., Labrador Retriever" value="{{ old('name') }}">
            </div>
            <div>
                <label style="display:block; font-weight:600; margin-bottom:.25rem;">Size</label>
                <input name="size" class="form-control" style="width:100%; padding:.6rem; border-radius:8px; border:1px solid #d1d5db;" placeholder="Small / Medium / Large" value="{{ old('size') }}">
            </div>
            <div>
                <label style="display:block; font-weight:600; margin-bottom:.25rem;">Notes</label>
                <input name="notes" class="form-control" style="width:100%; padding:.6rem; border-radius:8px; border:1px solid #d1d5db;" placeholder="Optional notes" value="{{ old('notes') }}">
            </div>
            <div>
                <button type="submit" style="padding:.7rem 1.1rem; border:none; border-radius:10px; background:#10b981; color:white; font-weight:600;">Save</button>
            </div>
        </form>
    </div>

    <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08); padding:1rem 1.25rem;">
        <h2 style="font-size:1.1rem; margin:0 0 .75rem 0;">Breeds</h2>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; min-width:720px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left; padding:.6rem;">Name</th>
                        <th style="text-align:left; padding:.6rem;">Slug</th>
                        <th style="text-align:left; padding:.6rem;">Size</th>
                        <th style="text-align:left; padding:.6rem;">Notes</th>
                        <th style="text-align:left; padding:.6rem; width:180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($breeds as $breed)
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:.6rem;">{{ $breed->name }}</td>
                            <td style="padding:.6rem; color:#6b7280;">{{ $breed->slug }}</td>
                            <td style="padding:.6rem;">{{ $breed->size ?? '—' }}</td>
                            <td style="padding:.6rem;">{{ $breed->notes ?? '—' }}</td>
                            <td style="padding:.6rem;">
                                <form action="{{ route('admin.breeds.update', $breed) }}" method="POST" style="display:flex; flex-direction:column; gap:.35rem; margin-bottom:.5rem;">
                                    @csrf
                                    @method('PUT')
                                    <input name="name" value="{{ old('name', $breed->name) }}" required style="width:100%; padding:.45rem .6rem; border-radius:8px; border:1px solid #d1d5db;" placeholder="Name">
                                    <div style="display:flex; gap:.35rem;">
                                        <input name="size" value="{{ old('size', $breed->size) }}" placeholder="Size" style="padding:.45rem .6rem; border-radius:8px; border:1px solid #d1d5db; flex:1;">
                                        <input name="notes" value="{{ old('notes', $breed->notes) }}" placeholder="Notes" style="padding:.45rem .6rem; border-radius:8px; border:1px solid #d1d5db; flex:2;">
                                    </div>
                                    <button type="submit" style="padding:.45rem .8rem; border:none; border-radius:8px; background:#2563eb; color:white; font-weight:600; align-self:flex-start;">Update</button>
                                </form>
                                <form action="{{ route('admin.breeds.destroy', $breed) }}" method="POST" onsubmit="return confirm('Delete this breed?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding:.45rem .8rem; border:none; border-radius:8px; background:#ef4444; color:white; font-weight:600;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:1rem; text-align:center; color:#6b7280;">No breeds yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;">
            {{ $breeds->links() }}
        </div>
    </div>
</div>
@endsection