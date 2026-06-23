<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receipts' => ['nullable', 'array'],

            'receipts.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }
    public function messages()
    {
        return [
            'receipts.*.mimes' => 'Only JPG, PNG and PDF files are allowed.',
            'receipts.*.max' => 'Each file must not exceed 2 MB.',
        ];
    }
}
