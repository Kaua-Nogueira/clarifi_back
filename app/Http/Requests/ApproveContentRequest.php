<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveContentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return ['comment' => ['nullable', 'string', 'max:1000'], 'actor_name' => ['nullable', 'string', 'max:100']];
    }
}
