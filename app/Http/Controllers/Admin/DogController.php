<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use App\Models\DogBreed;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class DogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','is_admin']);
    }

    public function index()
    {
        $dogs = Dog::with('breed')->latest()->paginate(12);
        return view('admin.dogs.index', compact('dogs'));
    }

    public function create()
    {
        $breeds = DogBreed::orderBy('name')->get();
        return view('admin.dogs.create', compact('breeds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable','string','max:255', Rule::unique('dogs','slug')],
            'breed_id' => 'nullable|exists:dog_breeds,id',
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

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('dogs/main', 'public');
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('dogs/gallery', 'public');
            }
            $data['gallery'] = $galleryPaths;
        }

        $data['vaccinated'] = $request->boolean('vaccinated');
        $data['sterilized'] = $request->boolean('sterilized');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        }

        Dog::create($data);

        return redirect()->route('admin.dogs.index')->with('success','Dog profile created.');
    }

    public function show(Dog $dog)
    {
        $dog->load('breed');
        return view('admin.dogs.show', compact('dog'));
    }

    public function edit(Dog $dog)
    {
        $breeds = DogBreed::orderBy('name')->get();
        return view('admin.dogs.edit', compact('dog','breeds'));
    }

    public function update(Request $request, Dog $dog)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable','string','max:255', Rule::unique('dogs','slug')->ignore($dog->id)],
            'breed_id' => 'nullable|exists:dog_breeds,id',
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

        if ($request->hasFile('main_image')) {
            if ($dog->main_image && Storage::disk('public')->exists($dog->main_image)) {
                Storage::disk('public')->delete($dog->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('dogs/main', 'public');
        }

        $gallery = $dog->gallery ?? [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('dogs/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        $data['vaccinated'] = $request->boolean('vaccinated');
        $data['sterilized'] = $request->boolean('sterilized');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        }

        $dog->update($data);

        return redirect()->route('admin.dogs.index')->with('success','Dog profile updated.');
    }

    public function destroy(Dog $dog)
    {
        if ($dog->main_image && Storage::disk('public')->exists($dog->main_image)) {
            Storage::disk('public')->delete($dog->main_image);
        }
        if ($dog->gallery) {
            foreach ($dog->gallery as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
        $dog->delete();
        return redirect()->route('admin.dogs.index')->with('success','Dog profile deleted.');
    }

    public function removeGalleryImage(Request $request, Dog $dog)
    {
        $path = $request->input('path');
        $gallery = $dog->gallery ?? [];
        if (($key = array_search($path, $gallery)) !== false) {
            unset($gallery[$key]);
            $gallery = array_values($gallery);
            $dog->gallery = $gallery;
            $dog->save();
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return response()->json(['ok' => true]);
        }
        return response()->json(['ok' => false], 404);
    }
}
