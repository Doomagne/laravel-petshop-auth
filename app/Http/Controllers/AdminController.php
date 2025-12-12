<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dog;
use App\Models\DogBreed;

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
        $dogs = Dog::with('breed')->orderBy('created_at', 'desc')->paginate(10);
        $breeds = DogBreed::orderBy('name')->get();

        return view('admin.dashboard', compact('dogs', 'breeds'));
    }
}
