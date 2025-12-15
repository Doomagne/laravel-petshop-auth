<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dog;
use App\Models\DogBreed;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Protect all admin routes.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Load dogs and dog breeds for the dashboard
        $dogs = Dog::with(['breed','mixBreed'])->orderBy('created_at', 'desc')->paginate(10);
        $breeds = DogBreed::orderBy('name')->get();

        return view('admin.dashboard', compact('dogs', 'breeds'));
    }

    /**
     * Quick-create a dog breed from the dashboard.
     */
    public function storeBreed(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('dog_breeds', 'name')],
        ]);

        // Generate a unique slug based on the name
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 1;
        while (DogBreed::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i++;
        }

        $data['slug'] = $slug;

        DogBreed::create($data);

        return back()->with('success', 'Breed added.');
    }
}

