<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoticeRequest;
use App\Http\Requests\UpdateNoticeRequest;
use App\Models\Notice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * Display the participants list for an event.
     */
    public function participants(Notice $notice): View
    {
        abort_unless($notice->isEvent() && $notice->hasRegistrationForm(), 404);

        $registrations = $notice->registrations()
            ->with('user')
            ->latest()
            ->paginate(30);

        return view('notices.participants', compact('notice', 'registrations'));
    }

    /**
     * Export event participants as CSV.
     */
    public function exportParticipants(Notice $notice): StreamedResponse
    {
        abort_unless($notice->isEvent() && $notice->hasRegistrationForm(), 404);

        $schema = $notice->form_schema;
        $registrations = $notice->registrations()->with('user')->latest()->get();

        $filename = 'participants-'.$notice->id.'-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($schema, $registrations) {
            $handle = fopen('php://output', 'w');

            // Header row
            $headers = ['Name', 'Alumni ID', 'Email', 'Registered At'];
            foreach ($schema as $field) {
                $headers[] = $field['label'];
            }
            fputcsv($handle, $headers);

            // Data rows
            foreach ($registrations as $registration) {
                $row = [
                    $registration->user->name,
                    $registration->user->alumni_id ?? '',
                    $registration->user->email,
                    $registration->created_at->format('Y-m-d H:i'),
                ];

                foreach ($schema as $field) {
                    $entry = $registration->form_data[$field['key']] ?? [];
                    $value = $entry['value'] ?? '';
                    $row[] = is_array($value) ? implode('|', $value) : $value;
                }

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
