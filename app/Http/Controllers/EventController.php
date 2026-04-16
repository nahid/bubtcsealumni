<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display the public event detail page with the dynamic registration form.
     */
    public function show(Notice $notice): View
    {
        abort_unless($notice->isEvent() && $notice->is_published, 404);

        $notice->loadCount('registrations');

        $registration = $notice->hasRegistrationForm()
            ? $notice->registrations()->where('user_id', auth()->id())->first()
            : null;

        return view('events.show', compact('notice', 'registration'));
    }

    /**
     * Handle event registration submission.
     */
    public function register(Request $request, Notice $notice): JsonResponse
    {
        abort_unless($notice->hasRegistrationForm(), 404);

        $existingRegistration = $notice->registrations()
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($existingRegistration) {
            return response()->json([
                'message' => 'You have already registered for this event.',
            ], 422);
        }

        $rules = $this->buildValidationRules($notice->form_schema);
        $validated = $request->validate($rules);

        // Store field values with label and type for readability
        $formData = collect($notice->form_schema)
            ->mapWithKeys(fn (array $field) => [
                $field['key'] => [
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'value' => $validated[$field['key']] ?? null,
                ],
            ])
            ->toArray();

        $notice->registrations()->create([
            'user_id' => $request->user()->id,
            'form_data' => $formData,
        ]);

        return response()->json([
            'message' => 'You have successfully registered for this event!',
        ]);
    }

    /**
     * Build Laravel validation rules from the form schema.
     *
     * @param  array<int, array<string, mixed>>  $schema
     * @return array<string, array<mixed>>
     */
    private function buildValidationRules(array $schema): array
    {
        $rules = [];

        foreach ($schema as $field) {
            $fieldRules = [];
            $fieldRules[] = ! empty($field['required']) ? 'required' : 'nullable';

            match ($field['type']) {
                'text' => array_push($fieldRules, 'string', 'max:255'),
                'textarea' => array_push($fieldRules, 'string', 'max:5000'),
                'select', 'radio' => array_push($fieldRules, 'string', 'in:'.implode(',', $field['options'] ?? [])),
                'checkbox' => array_push($fieldRules, 'array', 'min:1'),
                default => null,
            };

            $rules[$field['key']] = $fieldRules;

            if ($field['type'] === 'checkbox' && ! empty($field['options'])) {
                $rules[$field['key'].'.*'] = ['string', 'in:'.implode(',', $field['options'])];
            }
        }

        return $rules;
    }
}
