<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2000'],
            'content_asset_id' => ['nullable', 'integer', 'exists:content_assets,id'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
            'position_x' => ['nullable', 'numeric', 'between:0,100'],
            'position_y' => ['nullable', 'numeric', 'between:0,100'],
            'author_name' => ['nullable', 'string', 'max:100'],
            'author_role' => ['nullable', 'string', 'max:50'],
        ];
    }
}
