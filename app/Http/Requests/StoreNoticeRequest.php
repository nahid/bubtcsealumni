<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNoticeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->form_schema)) {
            $this->merge(['form_schema' => json_decode($this->form_schema, true)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
            'type' => ['required', Rule::in(['notice', 'event'])],
            'event_date' => ['nullable', 'required_if:type,event', 'date'],
            'is_published' => ['boolean'],
            'form_schema' => ['nullable', 'array'],
            'form_schema.*.key' => ['required_with:form_schema', 'string', 'max:50'],
            'form_schema.*.type' => ['required_with:form_schema', Rule::in(['text', 'textarea', 'select', 'radio', 'checkbox'])],
            'form_schema.*.label' => ['required_with:form_schema', 'string', 'max:255'],
            'form_schema.*.required' => ['boolean'],
            'form_schema.*.placeholder' => ['nullable', 'string', 'max:255'],
            'form_schema.*.options' => ['required_if:form_schema.*.type,select,radio,checkbox', 'array', 'min:1'],
            'form_schema.*.options.*' => ['string', 'max:255'],
        ];
    }
}
