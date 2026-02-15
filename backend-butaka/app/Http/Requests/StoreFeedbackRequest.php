<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    // untuk store feedback
    public function authorize(): bool
    {
        return true;
    }

    // validate store feedback
    public function rules(): array
    {
        return [
            'visitor_id' => ['required', 'exists:visitors,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
