<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\CatBreed;
use App\Models\Favorite;
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

        $favoritedCatIds = Favorite::query()
            ->where('user_id', auth()->id())
            ->where('favoritable_type', Cat::class)
            ->whereIn('favoritable_id', $cats->pluck('id'))
            ->pluck('favoritable_id')
            ->all();

        return view('cats.index', compact('cats', 'breeds', 'breedId', 'favoritedCatIds'));
    }

    public function show(Cat $cat)
    {
        $cat->load(['breed', 'mixBreed']);
        $isFavorited = Favorite::query()
            ->where('user_id', auth()->id())
            ->where('favoritable_type', Cat::class)
            ->where('favoritable_id', $cat->id)
            ->exists();

        return view('cats.show', compact('cat', 'isFavorited'));
    }
}




