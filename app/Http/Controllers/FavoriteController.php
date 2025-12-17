<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Dog;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
            ->with([
                'favoritable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Dog::class => ['breed', 'mixBreed'],
                        Cat::class => ['breed', 'mixBreed'],
                    ]);
                }
            ])
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function storeDog(Request $request, Dog $dog)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'favoritable_type' => Dog::class,
            'favoritable_id' => $dog->id,
        ]);

        return back()->with('success', 'Added to favorites.');
    }

    public function destroyDog(Request $request, Dog $dog)
    {
        Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', Dog::class)
            ->where('favoritable_id', $dog->id)
            ->delete();

        return back()->with('success', 'Removed from favorites.');
    }

    public function storeCat(Request $request, Cat $cat)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'favoritable_type' => Cat::class,
            'favoritable_id' => $cat->id,
        ]);

        return back()->with('success', 'Added to favorites.');
    }

    public function destroyCat(Request $request, Cat $cat)
    {
        Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', Cat::class)
            ->where('favoritable_id', $cat->id)
            ->delete();

        return back()->with('success', 'Removed from favorites.');
    }
}




