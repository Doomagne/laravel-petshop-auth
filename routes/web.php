<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DogPublicController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdoptionApplicationController;
use App\Http\Controllers\CatPublicController;

Route::get('/', function () {
    return redirect('/login');
});

// Guest routes (login, register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/register-admin', [AuthController::class, 'showRegisterAdmin'])->name('register-admin');
    Route::post('/register-admin', [AuthController::class, 'registerAdmin'])->name('register-admin.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $tasks = \App\Models\Task::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('dashboard', compact('tasks'));
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // To-Do List Routes
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // User-facing dog pages (read-only)
    Route::get('/dogs', [DogPublicController::class, 'index'])->name('dogs.index');
    Route::get('/dogs/{dog:slug}', [DogPublicController::class, 'show'])->name('dogs.show');

    // User-facing cat pages (read-only)
    Route::get('/cats', [CatPublicController::class, 'index'])->name('cats.index');
    Route::get('/cats/{cat:slug}', [CatPublicController::class, 'show'])->name('cats.show');

    // Favorites (user CRUD)
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/dogs/{dog:slug}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/dogs/{dog:slug}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // Adoption applications (user)
    Route::get('/applications', [AdoptionApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [AdoptionApplicationController::class, 'show'])->name('applications.show');
    Route::get('/dogs/{dog:slug}/apply', [AdoptionApplicationController::class, 'create'])->name('applications.create');
    Route::post('/dogs/{dog:slug}/apply', [AdoptionApplicationController::class, 'store'])->name('applications.store');
});

// Admin routes (protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::resource('dogs', \App\Http\Controllers\Admin\DogController::class);
    Route::resource('breeds', \App\Http\Controllers\Admin\DogBreedController::class)->except(['show','create','edit']);
     Route::post('dogs/{dog}/remove-gallery', 
        [\App\Http\Controllers\Admin\DogController::class, 'removeGalleryImage']
    )->name('dogs.removeGalleryImage');

    Route::resource('cats', \App\Http\Controllers\Admin\CatController::class);
    Route::resource('cat-breeds', \App\Http\Controllers\Admin\CatBreedController::class)->except(['show','create','edit']);
    Route::post('cats/{cat}/remove-gallery',
        [\App\Http\Controllers\Admin\CatController::class, 'removeGalleryImage']
    )->name('cats.removeGalleryImage');

    Route::get('applications', [\App\Http\Controllers\Admin\AdoptionApplicationController::class, 'index'])->name('applications.index');
    Route::get('applications/{application}', [\App\Http\Controllers\Admin\AdoptionApplicationController::class, 'show'])->name('applications.show');
    Route::patch('applications/{application}', [\App\Http\Controllers\Admin\AdoptionApplicationController::class, 'update'])->name('applications.update');
});