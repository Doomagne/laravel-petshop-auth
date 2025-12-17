@extends('layouts.app')

@section('title', 'Cats')

@section('content')
<div style="max-width: 1100px; margin: 2rem auto; padding: 1.5rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:.75rem; flex-wrap:wrap; margin-bottom:.75rem;">
        <h1 style="font-size:1.6rem; font-weight:700; color:#111827; margin:0;">Cats</h1>
        <a href="{{ route('admin.dashboard') }}" style="color:#2563eb; text-decoration:underline;">← Back to Dashboard</a>
    </div>
    <div style="display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1rem;">
        <a href="{{ route('admin.cats.create') }}" style="background:#2563eb; color:white; padding:0.55rem 1rem; border-radius:8px; text-decoration:none; font-weight:600;">+ Add Cat</a>
        <a href="{{ route('admin.cat-breeds.index') }}" style="background:#111827; color:white; padding:0.55rem 1rem; border-radius:8px; text-decoration:none; font-weight:600;">Manage Cat Breeds</a>
    </div>

    @if(session('success'))
        <div style="background:#ecfdf3; border:1px solid #bbf7d0; color:#166534; padding:.8rem 1rem; border-radius:10px; margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecdd3; color:#991b1b; padding:.8rem 1rem; border-radius:10px; margin-bottom:1rem;">
            <ul style="margin:0; padding-left:1.1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08); padding:1rem 1.25rem;">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; min-width:720px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left; padding:.6rem;">Name</th>
                        <th style="text-align:left; padding:.6rem;">Breed</th>
                        <th style="text-align:left; padding:.6rem;">Age</th>
                        <th style="text-align:left; padding:.6rem;">Status</th>
                        <th style="text-align:left; padding:.6rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cats as $cat)
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:.6rem;">{{ $cat->name }}</td>
                            <td style="padding:.6rem;">{{ $cat->breed_label }}</td>
                            <td style="padding:.6rem;">{{ $cat->age_label }}</td>
                            <td style="padding:.6rem;">{{ ucfirst($cat->status) }}</td>
                            <td style="padding:.6rem; display:flex; gap:.5rem; flex-wrap:wrap;">
                                <a href="{{ route('admin.cats.show', $cat) }}" style="color:#2563eb;">View</a>
                                <a href="{{ route('admin.cats.edit', $cat) }}" style="color:#f59e0b;">Edit</a>
                                <form action="{{ route('admin.cats.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete this cat?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="border:none; background:#ef4444; color:white; padding:.35rem .55rem; border-radius:8px; cursor:pointer;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:1rem; text-align:center; color:#6b7280;">No cats yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $cats->links() }}
        </div>
    </div>
</div>
@endsection



