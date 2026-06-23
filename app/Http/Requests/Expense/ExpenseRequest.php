<?php

namespace App\Http\Requests\Expense;

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
            'title' => 'required|string|max:255',

            'amount' => 'required|numeric|min:1',

            'expense_date' => 'required|date',

            'category_id' => 'required|exists:categories,id',

            'description' => 'nullable|string',

            'receipts' => 'nullable|array|min:1',

            'receipts.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'receipts.required' => 'Please upload at least one receipt.',

            'receipts.array' => 'Invalid receipt format.',

            'receipts.min' => 'Please upload at least one receipt.',

            'receipts.*.required' => 'Receipt file is required.',

            'receipts.*.file' => 'Uploaded file is invalid.',

            'receipts.*.mimes' => 'Only JPG, JPEG, PNG and PDF files are allowed.',

            'receipts.*.max' => 'Each file size must not exceed 2 MB.',
        ];
    }
}
