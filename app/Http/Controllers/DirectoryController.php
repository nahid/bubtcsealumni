<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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

        return view('directory.index', compact('alumni', 'intakes'));
    }
}
