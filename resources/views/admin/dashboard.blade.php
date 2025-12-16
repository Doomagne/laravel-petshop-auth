@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="color: #333; font-size: 2rem;">Admin Dashboard</h1>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background: #e74c3c; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    Logout
                </button>
            </form>
        </div>

        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 2rem; border: 1px solid #c3e6cb;">
            <strong>Welcome, {{ Auth::user()->name }}!</strong> You are logged in as an administrator.
        </div>

        <!-- cards omitted for brevity (keep your existing cards) -->

        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
            <h2 style="color: #333; margin-bottom: 1rem;">Admin Information</h2>
            <div style="display: grid; gap: 1rem;">
                <div><strong>Name:</strong> {{ Auth::user()->name }}</div>
                <div><strong>Email:</strong> {{ Auth::user()->email }}</div>
                <div><strong>Role:</strong> Administrator</div>
            </div>
        </div>

        {{-- DOGS MANAGEMENT SECTION --}}
        <div style="background: #fff; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.04);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <h2 style="margin:0">Dog Profiles</h2>
                <button id="openAddDogBtn" style="background:#4facfe; color:white; padding:0.5rem 1rem; border-radius:8px; border:none; cursor:pointer;">
                    + Add Dog
                </button>
            </div>

            {{-- Table --}}
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f1f3f5;">
                            <th style="padding:.6rem; text-align:left;">Photo</th>
                            <th style="padding:.6rem; text-align:left;">Name</th>
                            <th style="padding:.6rem; text-align:left;">Breed</th>
                            <th style="padding:.6rem; text-align:left;">Age</th>
                            <th style="padding:.6rem; text-align:left;">Status</th>
                            <th style="padding:.6rem; text-align:left;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dogs as $dog)
                        <tr style="border-top:1px solid #eee;">
                            <td style="padding:.6rem; vertical-align:middle;">
                                @if($dog->main_image)
                                    <img src="{{ asset('storage/'.$dog->main_image) }}" width="70" alt="{{ $dog->name }}" style="border-radius:8px;">
                                @else
                                    <div style="width:70px; height:50px; background:#eee; border-radius:6px;"></div>
                                @endif
                            </td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ $dog->name }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ $dog->breed?->name ?? 'N/A' }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ $dog->age_label }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ ucfirst($dog->status) }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">
                                <a href="{{ route('admin.dogs.show', $dog) }}" style="margin-right:.4rem; color:#0d6efd;">View</a>
                                <a href="{{ route('admin.dogs.edit', $dog) }}" style="margin-right:.4rem; color:#f59e0b;">Edit</a>

                                <form action="{{ route('admin.dogs.destroy', $dog) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this dog profile?')" style="background:#e74c3c; color:white; border:none; padding:6px 8px; border-radius:6px; cursor:pointer;">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="padding:1rem; text-align:center; color:#777;">No dogs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:1rem;">
                {{ $dogs->links() }}
            </div>
        </div>

        {{-- ADD DOG MODAL (simple) --}}
        <div id="addDogModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:9999;">
            <div style="background:#fff; width:900px; max-width:95%; border-radius:12px; padding:1.25rem; position:relative;">
                <button id="closeAddDogBtn" style="position:absolute; right:12px; top:12px; border:none; background:#eee; width:32px; height:32px; border-radius:6px; cursor:pointer;">✕</button>

                <h3 style="margin-top:0;">Add Dog</h3>

                <form action="{{ route('admin.dogs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div style="display:flex; gap:1rem;">
                        <div style="flex:1;">
                            <label style="display:block; font-weight:600; margin-bottom:.25rem;">Name</label>
                            <input name="name" required class="form-control" style="width:100%; padding:.6rem; border-radius:6px; border:1px solid #ddd;" value="{{ old('name') }}">

                            <label style="display:block; font-weight:600; margin: .75rem 0 .25rem;">Breed</label>
                            <select name="breed_id" class="form-control" style="width:100%; padding:.5rem; border-radius:6px; border:1px solid #ddd;">
                                <option value="">-- Select Breed --</option>
                                @foreach($breeds as $b)
                                    <option value="{{ $b->id }}" {{ old('breed_id') == $b->id ? 'selected':'' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>

                            <div style="display:flex; gap:1rem; margin-top:.75rem;">
                              <div style="flex:1;">
                                <label style="display:block; font-weight:600; margin-bottom:.25rem;">Age (months)</label>
                                <input name="age_months" type="number" class="form-control" style="width:100%; padding:.6rem; border-radius:6px; border:1px solid #ddd;" value="{{ old('age_months') }}">
                              </div>

                              <div style="flex:1;">
                                <label style="display:block; font-weight:600; margin-bottom:.25rem;">Gender</label>
                                <select name="gender" class="form-control" style="width:100%; padding:.5rem; border-radius:6px; border:1px solid #ddd;">
                                  <option value="unknown">Unknown</option>
                                  <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                                  <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                                </select>
                              </div>
                            </div>

                            <label style="display:block; font-weight:600; margin-top:.75rem;">Description</label>
                            <textarea name="description" rows="4" class="form-control" style="width:100%; padding:.6rem; border-radius:6px; border:1px solid #ddd;">{{ old('description') }}</textarea>

                            <div style="display:flex; gap:1rem; margin-top:.75rem;">
                                <label style="display:block; font-weight:600;">Vaccinated <input type="checkbox" name="vaccinated" value="1" {{ old('vaccinated') ? 'checked':'' }}></label>
                                <label style="display:block; font-weight:600;">Sterilized <input type="checkbox" name="sterilized" value="1" {{ old('sterilized') ? 'checked':'' }}></label>
                            </div>

                            <div style="margin-top:.75rem;">
                                <label style="display:block; font-weight:600;">Location</label>
                                <input name="location" class="form-control" style="width:100%; padding:.6rem; border-radius:6px; border:1px solid #ddd;" value="{{ old('location') }}">
                            </div>
                        </div>

                        <div style="width:320px;">
                            <label style="display:block; font-weight:600;">Main Photo</label>
                            <input type="file" name="main_image" accept="image/*" class="form-control-file">

                            <label style="display:block; font-weight:600; margin-top:.75rem;">Gallery (multiple)</label>
                            <input type="file" name="gallery[]" accept="image/*" multiple class="form-control-file">

                            <div id="previewGallery" style="display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.5rem;"></div>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:.5rem; margin-top:1rem;">
                        <button type="button" id="cancelAddDog" style="padding:.6rem 1rem; border-radius:8px; border:1px solid #ddd; background:#fff;">Cancel</button>
                        <button type="submit" style="padding:.6rem 1rem; border-radius:8px; border:none; background:#28a745; color:#fff;">Save</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- end modal --}}

    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const openBtn = document.getElementById('openAddDogBtn');
    const closeBtn = document.getElementById('closeAddDogBtn');
    const cancelBtn = document.getElementById('cancelAddDog');
    const modal = document.getElementById('addDogModal');

    openBtn?.addEventListener('click', ()=> modal.style.display = 'flex');
    closeBtn?.addEventListener('click', ()=> modal.style.display = 'none');
    cancelBtn?.addEventListener('click', ()=> modal.style.display = 'none');

    // preview gallery images
    const galleryInput = document.querySelector('input[name="gallery[]"]');
    const preview = document.getElementById('previewGallery');
    if (galleryInput) {
        galleryInput.addEventListener('change', (e) => {
            preview.innerHTML = '';
            Array.from(e.target.files).slice(0,10).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.style.width = '80px';
                    img.style.height = '60px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '6px';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });
    }
});
</script>
@endsection
