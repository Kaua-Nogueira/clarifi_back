<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $content = $this->route('content');

        return [
            'client_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'alpha_dash', 'max:180', Rule::unique('contents', 'slug')->ignore($content)],
            'channel' => ['required', 'string', 'max:80'],
            'type' => ['required', 'string', 'max:80'],
            'status' => ['required', Rule::in(['pending', 'approved', 'changes_requested', 'in_review', 'published', 'draft'])],
            'publication_date' => ['required', 'date'],
            'responsible' => ['required', 'string', 'max:120'],
            'objective' => ['required', 'string'],
            'editorial_line' => ['required', 'string', 'max:160'],
            'cta' => ['required', 'string', 'max:180'],
            'audience' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'agency_notes' => ['nullable', 'string'],
            'version_notes' => ['nullable', 'string', 'max:500'],
            'assets' => ['array', 'max:12'],
            'assets.*.url' => ['required', 'string', 'max:1000'],
            'assets.*.alt_text' => ['nullable', 'string', 'max:255'],
            'assets.*.kind' => ['nullable', Rule::in(['image', 'video'])],
        ];
    }
}
