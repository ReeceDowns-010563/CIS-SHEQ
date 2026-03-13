<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendTestEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'recipient_email' => 'required|email|max:255',
            'variables' => 'sometimes|array',
            'variables.*' => 'string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_email.required' => 'Please enter a recipient email address.',
            'recipient_email.email' => 'Please enter a valid email address.',
            'variables.*.string' => 'Variable values must be strings.',
            'variables.*.max' => 'Variable values cannot exceed 1000 characters.',
        ];
    }
}
