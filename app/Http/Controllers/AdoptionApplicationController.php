<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\Dog;
use Illuminate\Http\Request;

class AdoptionApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $applications = AdoptionApplication::query()
            ->where('user_id', auth()->id())
            ->with(['dog.breed', 'dog.mixBreed'])
            ->latest()
            ->paginate(12);

        return view('applications.index', compact('applications'));
    }

    public function show(AdoptionApplication $application)
    {
        abort_unless($application->user_id === auth()->id(), 403);

        $application->load(['dog.breed', 'dog.mixBreed']);
        return view('applications.show', compact('application'));
    }

    public function create(Dog $dog)
    {
        $existing = AdoptionApplication::query()
            ->where('user_id', auth()->id())
            ->where('dog_id', $dog->id)
            ->first();

        if ($existing) {
            return redirect()->route('applications.show', $existing)->with('success', 'You already applied for this dog.');
        }

        if ($dog->status === 'adopted') {
            return redirect()->route('dogs.show', ['dog' => $dog->slug])->with('success', 'This dog is already adopted.');
        }

        return view('applications.create', compact('dog'));
    }

    public function store(Request $request, Dog $dog)
    {
        $existing = AdoptionApplication::query()
            ->where('user_id', auth()->id())
            ->where('dog_id', $dog->id)
            ->first();

        if ($existing) {
            return redirect()->route('applications.show', $existing)->with('success', 'You already applied for this dog.');
        }

        if ($dog->status === 'adopted') {
            return redirect()->route('dogs.show', ['dog' => $dog->slug])->with('success', 'This dog is already adopted.');
        }

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:2000',
            'message' => 'nullable|string|max:5000',
        ]);

        $data['user_id'] = auth()->id();
        $data['dog_id'] = $dog->id;
        $data['status'] = 'pending';

        $application = AdoptionApplication::create($data);

        return redirect()
            ->route('applications.show', $application)
            ->with('success', 'Application submitted! The admin will review it.');
    }
}



