<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DirectoryController extends Controller
{
    /**
     * Display the searchable alumni directory.
     */
    public function index(Request $request): View
    {
        $query = User::where('status', 'verified')
            ->latest('name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('intake', $search);
            });
        }

        if ($intake = $request->input('intake')) {
            $query->where('intake', $intake);
        }

        if ($shift = $request->input('shift')) {
            $query->where('shift', $shift);
        }

        $alumni = $query->paginate(12)->withQueryString();

        $intakes = User::where('status', 'verified')
            ->distinct()
            ->orderBy('intake')
            ->pluck('intake');

        // Alumni with location data for map view
        $mapAlumni = User::where('status', 'verified')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select(['id', 'name', 'intake', 'shift', 'profile_photo', 'company_name', 'designation', 'current_city', 'latitude', 'longitude'])
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'intake' => $u->intake,
                'shift' => $u->shift,
                'photo' => $u->profile_photo ? Storage::disk('public')->url($u->profile_photo) : null,
                'company' => $u->company_name,
                'designation' => $u->designation,
                'city' => $u->current_city,
                'lat' => (float) $u->latitude,
                'lng' => (float) $u->longitude,
                'url' => route('profile.show', $u->id),
            ]);

        return view('directory.index', compact('alumni', 'intakes', 'mapAlumni'));
    }
}
