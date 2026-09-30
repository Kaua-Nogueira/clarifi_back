<?php

namespace App\Services;

use App\Models\Content;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AdminContentService
{
    public function create(array $data): Content
    {
        return DB::transaction(function () use ($data) {
            $assets = Arr::pull($data, 'assets', []);
            $versionNotes = Arr::pull($data, 'version_notes', 'Versão inicial criada pela agência.');
            $content = Content::create($data);
            $this->syncAssets($content, $assets);
            $content->versions()->create([
                'version_number' => 1,
                'notes' => $versionNotes ?: 'Versão inicial criada pela agência.',
                'status' => 'current',
            ]);
            return $content;
        });
    }

    public function update(Content $content, array $data): Content
    {
        return DB::transaction(function () use ($content, $data) {
            $assets = Arr::pull($data, 'assets', []);
            $versionNotes = Arr::pull($data, 'version_notes');
            $content->update($data);
            $this->syncAssets($content, $assets);

            if ($versionNotes) {
                $content->versions()->where('status', 'current')->update(['status' => 'superseded']);
                $content->versions()->create([
                    'version_number' => ((int) $content->versions()->max('version_number')) + 1,
                    'notes' => $versionNotes,
                    'status' => 'current',
                ]);
            }

            return $content;
        });
    }

    private function syncAssets(Content $content, array $assets): void
    {
        $content->assets()->delete();
        foreach ($assets as $index => $asset) {
            $content->assets()->create([
                'kind' => $asset['kind'] ?? 'image',
                'url' => $asset['url'],
                'alt_text' => $asset['alt_text'] ?? $content->title,
                'position' => $index + 1,
            ]);
        }
    }
}
