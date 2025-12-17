<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdoptionApplication;
use Illuminate\Http\Request;

class AdoptionApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $status = $request->query('status'); // pending|approved|rejected|null

        $applications = AdoptionApplication::query()
            ->with(['user', 'dog.breed', 'dog.mixBreed'])
            ->when($status, function ($q) use ($status) {
                $allowed = ['pending', 'approved', 'rejected'];
                if (in_array($status, $allowed, true)) {
                    $q->where('status', $status);
                }
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.applications.index', compact('applications', 'status'));
    }

    public function show(AdoptionApplication $application)
    {
        $application->load(['user', 'dog.breed', 'dog.mixBreed']);
        return view('admin.applications.show', compact('application'));
    }

    public function update(Request $request, AdoptionApplication $application)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:5000',
        ]);

        $application->update($data);

        return back()->with('success', 'Application updated.');
    }
}



