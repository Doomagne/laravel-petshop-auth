@extends('layouts.app')

@section('title', $dog->name)

@section('content')
<div style="max-width: 1100px; margin: 2rem auto; padding: 1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h1 style="font-size:1.6rem; font-weight:700; color:#111827;">{{ $dog->name }}</h1>
        <a href="{{ route('admin.dogs.index') }}" style="color:#2563eb; text-decoration:underline;">← Back to Dogs</a>
    </div>

    <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08); padding:1.25rem; display:grid; gap:1rem; grid-template-columns: 320px 1fr; align-items:start;">
        <div>
            <div style="width:100%; border-radius:12px; overflow:hidden; background:#f3f4f6; min-height:220px; display:flex; align-items:center; justify-content:center;">
                @if($dog->main_image_url)
                    <img src="{{ $dog->main_image_url }}" alt="{{ $dog->name }}" style="width:100%; object-fit:cover;">
                @else
                    <span style="color:#9ca3af;">No main photo</span>
                @endif
            </div>

            @if($dog->gallery_urls)
                <div style="display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.75rem;">
                    @foreach($dog->gallery_urls as $url)
                        <img src="{{ $url }}" alt="Gallery" style="width:90px; height:70px; object-fit:cover; border-radius:8px; background:#f3f4f6;">
                    @endforeach
                </div>
            @endif
        </div>

        <div style="display:grid; gap:.6rem;">
            <div><strong>Breed:</strong> {{ $dog->breed_label }}</div>
            <div><strong>Age:</strong> {{ $dog->age_label }}</div>
            <div><strong>Gender:</strong> {{ ucfirst($dog->gender ?? 'unknown') }}</div>
            <div><strong>Status:</strong> {{ ucfirst($dog->status) }}</div>
            <div><strong>Vaccinated:</strong> {{ $dog->vaccinated ? 'Yes' : 'No' }}</div>
            <div><strong>Sterilized:</strong> {{ $dog->sterilized ? 'Yes' : 'No' }}</div>
            @if($dog->description)
                <div><strong>Description:</strong><br>{{ $dog->description }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

