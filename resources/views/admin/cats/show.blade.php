@extends('layouts.app')

@section('title', $cat->name)

@section('content')
<div style="max-width: 1100px; margin: 2rem auto; padding: 1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h1 style="font-size:1.6rem; font-weight:700; color:#111827;">{{ $cat->name }}</h1>
        <a href="{{ route('admin.cats.index') }}" style="color:#2563eb; text-decoration:underline;">← Back to Cats</a>
    </div>

    <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08); padding:1.25rem; display:grid; gap:1rem; grid-template-columns: 320px 1fr; align-items:start;">
        <div>
            <div style="width:100%; border-radius:12px; overflow:hidden; background:#f3f4f6; min-height:220px; display:flex; align-items:center; justify-content:center;">
                @if($cat->main_image_url)
                    <img src="{{ $cat->main_image_url }}" alt="{{ $cat->name }}" style="width:100%; object-fit:cover;">
                @else
                    <span style="color:#9ca3af;">No main photo</span>
                @endif
            </div>

            @if($cat->gallery_urls)
                <div style="display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.75rem;">
                    @foreach($cat->gallery_urls as $url)
                        <img src="{{ $url }}" alt="Gallery" style="width:90px; height:70px; object-fit:cover; border-radius:8px; background:#f3f4f6;">
                    @endforeach
                </div>
            @endif
        </div>

        <div style="display:grid; gap:.6rem;">
            <div><strong>Breed:</strong> {{ $cat->breed_label }}</div>
            <div><strong>Age:</strong> {{ $cat->age_label }}</div>
            <div><strong>Gender:</strong> {{ ucfirst($cat->gender ?? 'unknown') }}</div>
            <div><strong>Status:</strong> {{ ucfirst($cat->status) }}</div>
            <div><strong>Vaccinated:</strong> {{ $cat->vaccinated ? 'Yes' : 'No' }}</div>
            <div><strong>Sterilized:</strong> {{ $cat->sterilized ? 'Yes' : 'No' }}</div>
            @if($cat->location)
                <div><strong>Location:</strong> {{ $cat->location }}</div>
            @endif
            @if($cat->description)
                <div><strong>Description:</strong><br>{{ $cat->description }}</div>
            @endif
        </div>
    </div>
</div>
@endsection




