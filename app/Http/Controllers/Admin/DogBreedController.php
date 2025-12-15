<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DogBreed;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class DogBreedController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $breeds = DogBreed::orderBy('name')->paginate(15);
        return view('admin.breeds.index', compact('breeds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('dog_breeds', 'name')],
            'size' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['slug'] = $this->makeUniqueSlug($data['name']);

        DogBreed::create($data);

        return back()->with('success', 'Breed added.');
    }

    public function update(Request $request, DogBreed $breed)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('dog_breeds', 'name')->ignore($breed->id)],
            'size' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        // Regenerate slug if the name changes
        if ($data['name'] !== $breed->name) {
            $data['slug'] = $this->makeUniqueSlug($data['name'], $breed->id);
        }

        $breed->update($data);

        return back()->with('success', 'Breed updated.');
    }

    public function destroy(DogBreed $breed)
    {
        $breed->delete();
        return back()->with('success', 'Breed deleted.');
    }

    private function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (
            DogBreed::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '<>', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}

