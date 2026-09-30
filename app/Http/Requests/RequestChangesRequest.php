<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestChangesRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
            'priority' => ['required', 'in:low,normal,high'],
            'actor_name' => ['nullable', 'string', 'max:100'],
        ];
    }
}
