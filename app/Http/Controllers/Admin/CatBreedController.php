<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatBreed;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CatBreedController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $breeds = CatBreed::orderBy('name')->paginate(15);
        return view('admin.cat_breeds.index', compact('breeds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('cat_breeds', 'name')],
            'size' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['slug'] = $this->makeUniqueSlug($data['name']);

        CatBreed::create($data);

        return back()->with('success', 'Breed added.');
    }

    public function update(Request $request, CatBreed $cat_breed)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('cat_breeds', 'name')->ignore($cat_breed->id)],
            'size' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($data['name'] !== $cat_breed->name) {
            $data['slug'] = $this->makeUniqueSlug($data['name'], $cat_breed->id);
        }

        $cat_breed->update($data);

        return back()->with('success', 'Breed updated.');
    }

    public function destroy(CatBreed $cat_breed)
    {
        $cat_breed->delete();
        return back()->with('success', 'Breed deleted.');
    }

    private function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (
            CatBreed::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '<>', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}



