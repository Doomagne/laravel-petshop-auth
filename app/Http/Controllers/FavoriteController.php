<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $favorites = Favorite::query()
            ->where('user_id', auth()->id())
            ->with(['dog.breed', 'dog.mixBreed'])
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request, Dog $dog)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'dog_id' => $dog->id,
        ]);

        return back()->with('success', 'Added to favorites.');
    }

    public function destroy(Request $request, Dog $dog)
    {
        Favorite::where('user_id', auth()->id())
            ->where('dog_id', $dog->id)
            ->delete();

        return back()->with('success', 'Removed from favorites.');
    }
}



