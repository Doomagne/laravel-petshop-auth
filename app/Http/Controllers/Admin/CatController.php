<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use App\Models\CatBreed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $cats = Cat::with(['breed', 'mixBreed'])->latest()->paginate(12);
        return view('admin.cats.index', compact('cats'));
    }

    public function create()
    {
        $breeds = CatBreed::orderBy('name')->get();
        return view('admin.cats.create', compact('breeds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('cats', 'slug')],
            'breed_id' => 'nullable|exists:cat_breeds,id',
            'is_mix' => 'sometimes|boolean',
            'mix_breed_id' => 'nullable|exists:cat_breeds,id',
            'age_months' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female,unknown',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|max:4096',
            'gallery.*' => 'nullable|image|max:4096',
            'vaccinated' => 'sometimes|boolean',
            'sterilized' => 'sometimes|boolean',
            'status' => 'required|in:available,adopted,fostered,pending',
            'location' => 'nullable|string|max:255',
        ]);

        $data['is_mix'] = $request->boolean('is_mix');

        if (! $data['is_mix']) {
            $data['mix_breed_id'] = null;
        } elseif (!empty($data['mix_breed_id']) && $data['mix_breed_id'] == $data['breed_id']) {
            return back()->withErrors(['mix_breed_id' => 'Choose a different second breed.'])->withInput();
        }

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('cats/main', 'public');
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('cats/gallery', 'public');
            }
            $data['gallery'] = $galleryPaths;
        }

        $data['vaccinated'] = $request->boolean('vaccinated');
        $data['sterilized'] = $request->boolean('sterilized');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        }

        Cat::create($data);

        return redirect()->route('admin.cats.index')->with('success', 'Cat profile created.');
    }

    public function show(Cat $cat)
    {
        $cat->load(['breed', 'mixBreed']);
        return view('admin.cats.show', compact('cat'));
    }

    public function edit(Cat $cat)
    {
        $breeds = CatBreed::orderBy('name')->get();
        return view('admin.cats.edit', compact('cat', 'breeds'));
    }

    public function update(Request $request, Cat $cat)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('cats', 'slug')->ignore($cat->id)],
            'breed_id' => 'nullable|exists:cat_breeds,id',
            'is_mix' => 'sometimes|boolean',
            'mix_breed_id' => 'nullable|exists:cat_breeds,id',
            'age_months' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female,unknown',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|max:4096',
            'gallery.*' => 'nullable|image|max:4096',
            'vaccinated' => 'sometimes|boolean',
            'sterilized' => 'sometimes|boolean',
            'status' => 'required|in:available,adopted,fostered,pending',
            'location' => 'nullable|string|max:255',
        ]);

        $data['is_mix'] = $request->boolean('is_mix');

        if (! $data['is_mix']) {
            $data['mix_breed_id'] = null;
        } elseif (!empty($data['mix_breed_id']) && $data['mix_breed_id'] == $data['breed_id']) {
            return back()->withErrors(['mix_breed_id' => 'Choose a different second breed.'])->withInput();
        }

        if ($request->hasFile('main_image')) {
            if ($cat->main_image && Storage::disk('public')->exists($cat->main_image)) {
                Storage::disk('public')->delete($cat->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('cats/main', 'public');
        }

        $gallery = $cat->gallery ?? [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('cats/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        $data['vaccinated'] = $request->boolean('vaccinated');
        $data['sterilized'] = $request->boolean('sterilized');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        }

        $cat->update($data);

        return redirect()->route('admin.cats.index')->with('success', 'Cat profile updated.');
    }

    public function destroy(Cat $cat)
    {
        if ($cat->main_image && Storage::disk('public')->exists($cat->main_image)) {
            Storage::disk('public')->delete($cat->main_image);
        }
        if ($cat->gallery) {
            foreach ($cat->gallery as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $cat->delete();

        return redirect()->route('admin.cats.index')->with('success', 'Cat profile deleted.');
    }

    public function removeGalleryImage(Request $request, Cat $cat)
    {
        $path = $request->input('path');
        $gallery = $cat->gallery ?? [];
        if (($key = array_search($path, $gallery)) !== false) {
            unset($gallery[$key]);
            $gallery = array_values($gallery);
            $cat->gallery = $gallery;
            $cat->save();
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false], 404);
    }
}



