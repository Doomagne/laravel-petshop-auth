<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\AdoptionApplication;
use App\Models\Dog;
use App\Models\DogBreed;
use Illuminate\Http\Request;

class DogPublicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $breed = $request->query('breed'); // dog_breeds.id|null
        $breedId = is_numeric($breed) ? (int) $breed : null;

        $breeds = DogBreed::query()->orderBy('name')->get();

        $dogs = Dog::query()
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

        $favoritedDogIds = Favorite::query()
            ->where('user_id', auth()->id())
            ->whereIn('dog_id', $dogs->pluck('id'))
            ->pluck('dog_id')
            ->all();

        return view('dogs.index', compact('dogs', 'breeds', 'breedId', 'favoritedDogIds'));
    }

    public function show(Dog $dog)
    {
        $dog->load(['breed', 'mixBreed']);
        $isFavorited = Favorite::query()
            ->where('user_id', auth()->id())
            ->where('dog_id', $dog->id)
            ->exists();

        $existingApplication = AdoptionApplication::query()
            ->where('user_id', auth()->id())
            ->where('dog_id', $dog->id)
            ->first();

        return view('dogs.show', [
            'dog' => $dog,
            'isFavorited' => $isFavorited,
            'existingApplication' => $existingApplication,
        ]);
    }
}


