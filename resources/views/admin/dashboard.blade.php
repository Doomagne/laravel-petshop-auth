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

        @if(session('success'))
            <div style="background:#ecfdf3; border:1px solid #bbf7d0; color:#166534; padding:1rem; border-radius:10px; margin-bottom:1rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#fef2f2; border:1px solid #fecdd3; color:#991b1b; padding:1rem; border-radius:10px; margin-bottom:1rem;">
                <ul style="margin:0; padding-left:1.1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                    <a href="{{ route('admin.applications.index') }}" style="background:#16a34a; color:white; padding:0.5rem 1rem; border-radius:8px; border:none; cursor:pointer; text-decoration:none; display:inline-block;">
                        Adoption Applications
                    </a>
                    <a href="{{ route('admin.breeds.index') }}" style="background:#111827; color:white; padding:0.5rem 1rem; border-radius:8px; border:none; cursor:pointer; text-decoration:none; display:inline-block;">
                        Manage Breeds
                    </a>
                    <a href="{{ route('admin.dogs.index') }}" style="background:#2563eb; color:white; padding:0.5rem 1rem; border-radius:8px; border:none; cursor:pointer; text-decoration:none; display:inline-block;">
                        Manage Dogs
                    </a>
                    <a href="{{ route('admin.cat-breeds.index') }}" style="background:#111827; color:white; padding:0.5rem 1rem; border-radius:8px; border:none; cursor:pointer; text-decoration:none; display:inline-block;">
                        Manage Cat Breeds
                    </a>
                    <a href="{{ route('admin.cats.index') }}" style="background:#2563eb; color:white; padding:0.5rem 1rem; border-radius:8px; border:none; cursor:pointer; text-decoration:none; display:inline-block;">
                        Manage Cats
                    </a>
                </div>
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
                            <td style="padding:.6rem; vertical-align:middle;">{{ $dog->breed_label }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ $dog->age_label }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ ucfirst($dog->status) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="padding:1rem; text-align:center; color:#777;">No dogs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:1rem;">
                {{ $dogs->links() }}
            </div>
        </div>

        {{-- CATS MANAGEMENT SECTION --}}
        <div style="background: #fff; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.04); margin-top: 1.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <h2 style="margin:0">Cat Profiles</h2>
                <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                    <a href="{{ route('admin.cat-breeds.index') }}" style="background:#111827; color:white; padding:0.5rem 1rem; border-radius:8px; border:none; cursor:pointer; text-decoration:none; display:inline-block;">
                        Manage Cat Breeds
                    </a>
                    <a href="{{ route('admin.cats.index') }}" style="background:#2563eb; color:white; padding:0.5rem 1rem; border-radius:8px; border:none; cursor:pointer; text-decoration:none; display:inline-block;">
                        Manage Cats
                    </a>
                </div>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cats as $cat)
                        <tr style="border-top:1px solid #eee;">
                            <td style="padding:.6rem; vertical-align:middle;">
                                @if($cat->main_image)
                                    <img src="{{ asset('storage/'.$cat->main_image) }}" width="70" alt="{{ $cat->name }}" style="border-radius:8px;">
                                @else
                                    <div style="width:70px; height:50px; background:#eee; border-radius:6px;"></div>
                                @endif
                            </td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ $cat->name }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ $cat->breed_label }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ $cat->age_label }}</td>
                            <td style="padding:.6rem; vertical-align:middle;">{{ ucfirst($cat->status) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="padding:1rem; text-align:center; color:#777;">No cats yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:1rem;">
                {{ $cats->links() }}
            </div>
        </div>

        {{-- Add/Create modals removed from dashboard; use Manage pages instead --}}

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
    const openBreedBtn = document.getElementById('openAddBreedBtn');
    const closeBreedBtn = document.getElementById('closeAddBreedBtn');
    const cancelBreedBtn = document.getElementById('cancelAddBreed');
    const breedModal = document.getElementById('addBreedModal');
    const isMixCheckbox = document.getElementById('isMixCheckbox');
    const mixWrapper = document.getElementById('mixBreedWrapper');
    const mixSelect = document.getElementById('mixBreedSelect');

    openBtn?.addEventListener('click', ()=> modal.style.display = 'flex');
    closeBtn?.addEventListener('click', ()=> modal.style.display = 'none');
    cancelBtn?.addEventListener('click', ()=> modal.style.display = 'none');

    openBreedBtn?.addEventListener('click', ()=> breedModal.style.display = 'flex');
    closeBreedBtn?.addEventListener('click', ()=> breedModal.style.display = 'none');
    cancelBreedBtn?.addEventListener('click', ()=> breedModal.style.display = 'none');

    const toggleMix = () => {
        if (isMixCheckbox?.checked) {
            mixWrapper.style.display = 'block';
        } else {
            mixWrapper.style.display = 'none';
            if (mixSelect) mixSelect.value = '';
        }
    };
    isMixCheckbox?.addEventListener('change', toggleMix);
    toggleMix();

    // preview gallery images
    // Dashboard no longer contains add/edit modals; use Manage pages.
});
</script>
@endsection