@extends('layouts.app')

@section('title', 'Edit '.$dog->name)

@section('content')
<div style="max-width: 1100px; margin: 2rem auto; padding: 1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h1 style="font-size:1.6rem; font-weight:700; color:#111827;">Edit Dog</h1>
        <a href="{{ route('admin.dogs.index') }}" style="color:#2563eb; text-decoration:underline;">← Back to Dogs</a>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecdd3; color:#991b1b; padding:.8rem 1rem; border-radius:10px; margin-bottom:1rem;">
            <ul style="margin:0; padding-left:1.1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08); padding:1.25rem;">
        <form action="{{ route('admin.dogs.update', $dog) }}" method="POST" enctype="multipart/form-data" style="display:flex; gap:1.25rem; flex-wrap:wrap;">
            @csrf
            @method('PUT')

            <div style="flex:1; min-width:320px;">
                <label style="display:block; font-weight:600; margin-bottom:.25rem;">Name</label>
                <input name="name" required value="{{ old('name', $dog->name) }}" style="width:100%; padding:.6rem; border-radius:8px; border:1px solid #d1d5db;">

                <label style="display:block; font-weight:600; margin:.75rem 0 .25rem;">Breed</label>
                <select name="breed_id" style="width:100%; padding:.55rem; border-radius:8px; border:1px solid #d1d5db;">
                    <option value="">-- Select Breed --</option>
                    @foreach($breeds as $b)
                        <option value="{{ $b->id }}" {{ old('breed_id', $dog->breed_id) == $b->id ? 'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>

                <div style="margin-top:.6rem; display:flex; align-items:center; gap:.5rem;">
                    <input type="checkbox" name="is_mix" id="isMixCheckbox" value="1" {{ old('is_mix', $dog->is_mix) ? 'checked' : '' }} />
                    <label for="isMixCheckbox" style="font-weight:600; margin:0;">Mixed breed</label>
                </div>

                <div id="mixBreedWrapper" style="margin-top:.5rem; display:{{ old('is_mix', $dog->is_mix) ? 'block':'none' }};">
                    <label style="display:block; font-weight:600; margin-bottom:.25rem;">Second Breed</label>
                    <select name="mix_breed_id" id="mixBreedSelect" style="width:100%; padding:.55rem; border-radius:8px; border:1px solid #d1d5db;">
                        <option value="">-- Select Second Breed --</option>
                        @foreach($breeds as $b)
                            <option value="{{ $b->id }}" {{ old('mix_breed_id', $dog->mix_breed_id) == $b->id ? 'selected':'' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; gap:1rem; margin-top:.75rem;">
                    <div style="flex:1;">
                        <label style="display:block; font-weight:600; margin-bottom:.25rem;">Age (months)</label>
                        <input name="age_months" type="number" value="{{ old('age_months', $dog->age_months) }}" style="width:100%; padding:.6rem; border-radius:8px; border:1px solid #d1d5db;">
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-weight:600; margin-bottom:.25rem;">Gender</label>
                        <select name="gender" style="width:100%; padding:.55rem; border-radius:8px; border:1px solid #d1d5db;">
                            <option value="unknown" {{ old('gender', $dog->gender) === 'unknown' ? 'selected':'' }}>Unknown</option>
                            <option value="male" {{ old('gender', $dog->gender) === 'male' ? 'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender', $dog->gender) === 'female' ? 'selected':'' }}>Female</option>
                        </select>
                    </div>
                </div>

                <label style="display:block; font-weight:600; margin-top:.75rem;">Description</label>
                <textarea name="description" rows="4" style="width:100%; padding:.6rem; border-radius:8px; border:1px solid #d1d5db;">{{ old('description', $dog->description) }}</textarea>

                <div style="display:flex; gap:1rem; margin-top:.75rem;">
                    <label style="display:block; font-weight:600;">Vaccinated
                        <input type="checkbox" name="vaccinated" value="1" {{ old('vaccinated', $dog->vaccinated) ? 'checked':'' }}>
                    </label>
                    <label style="display:block; font-weight:600;">Sterilized
                        <input type="checkbox" name="sterilized" value="1" {{ old('sterilized', $dog->sterilized) ? 'checked':'' }}>
                    </label>
                </div>

                <label style="display:block; font-weight:600; margin-top:.75rem;">Status</label>
                <select name="status" style="width:100%; padding:.55rem; border-radius:8px; border:1px solid #d1d5db;">
                    @foreach(['available','adopted','fostered','pending'] as $s)
                        <option value="{{ $s }}" {{ old('status', $dog->status) === $s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width:320px; max-width:100%;">
                <label style="display:block; font-weight:600;">Main Photo</label>
                @if($dog->main_image_url)
                    <div style="margin: .35rem 0; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; background:#f9fafb;">
                        <img src="{{ $dog->main_image_url }}" alt="Current main" style="width:100%; object-fit:cover;">
                    </div>
                @endif
                <input type="file" name="main_image" accept="image/*" class="form-control-file">

                <label style="display:block; font-weight:600; margin-top:.75rem;">Gallery (add more)</label>
                <input type="file" name="gallery[]" accept="image/*" multiple class="form-control-file">

                @if($dog->gallery_urls)
                    <div style="display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.5rem;">
                        @foreach($dog->gallery_urls as $url)
                            <img src="{{ $url }}" alt="Gallery" style="width:80px; height:60px; object-fit:cover; border-radius:8px; background:#f3f4f6;">
                        @endforeach
                    </div>
                @endif
            </div>

            <div style="width:100%; display:flex; justify-content:flex-end; gap:.5rem; margin-top:1rem;">
                <a href="{{ route('admin.dogs.index') }}" style="padding:.65rem 1rem; border-radius:8px; border:1px solid #d1d5db; background:#fff; text-decoration:none; color:#111827;">Cancel</a>
                <button type="submit" style="padding:.65rem 1.1rem; border:none; border-radius:8px; background:#2563eb; color:white; font-weight:600;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection