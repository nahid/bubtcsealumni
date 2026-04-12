<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoticeRequest;
use App\Http\Requests\UpdateNoticeRequest;
use App\Models\Notice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class NoticeController extends Controller
{
    /**
     * Display all notices for admin management.
     */
    public function index(): View
    {
        $notices = Notice::with('user')
            ->latest()
            ->paginate(15);

        return view('notices.index', compact('notices'));
    }

    /**
     * Show the form for creating a new notice.
     */
    public function create(): View
    {
        return view('notices.create');
    }

    /**
     * Store a newly created notice.
     */
    public function store(StoreNoticeRequest $request): RedirectResponse
    {
        $request->user()->notices()->create($request->validated());

        return redirect()->route('notices.index')
            ->with('success', 'Notice published successfully!');
    }

    /**
     * Show the form for editing a notice.
     */
    public function edit(Notice $notice): View
    {
        return view('notices.edit', compact('notice'));
    }

    /**
     * Update the specified notice.
     */
    public function update(UpdateNoticeRequest $request, Notice $notice): RedirectResponse
    {
        $notice->update($request->validated());

        return redirect()->route('notices.index')
            ->with('success', 'Notice updated successfully!');
    }

    /**
     * Delete the specified notice.
     */
    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('notices.index')
            ->with('success', 'Notice deleted successfully!');
    }
}
