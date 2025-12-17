<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\CatBreed;
use Illuminate\Http\Request;

class CatPublicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $breed = $request->query('breed');
        $breedId = is_numeric($breed) ? (int) $breed : null;

        $breeds = CatBreed::query()->orderBy('name')->get();

        $cats = Cat::query()
            ->with(['breed', 'mixBreed'])
            ->when($breedId, function ($query) use ($breedId) {
                $query->where(function ($sub) use ($breedId) {
                    $sub->where('breed_id', $breedId)
                        ->orWhere('mix_breed_id', $breedId);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('cats.index', compact('cats', 'breeds', 'breedId'));
    }

    public function show(Cat $cat)
    {
        $cat->load(['breed', 'mixBreed']);
        return view('cats.show', compact('cat'));
    }
}



