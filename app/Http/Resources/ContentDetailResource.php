<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'client' => ['id' => $this->client->id, 'name' => $this->client->name],
            'channel' => $this->channel,
            'type' => $this->type,
            'status' => $this->status,
            'publication_date' => $this->publication_date->toISOString(),
            'responsible' => $this->responsible,
            'objective' => $this->objective,
            'editorial_line' => $this->editorial_line,
            'cta' => $this->cta,
            'audience' => $this->audience,
            'caption' => $this->caption,
            'agency_notes' => $this->agency_notes,
            'assets' => $this->assets->map(fn ($asset) => [
                'id' => $asset->id,
                'kind' => $asset->kind,
                'url' => $asset->url,
                'alt_text' => $asset->alt_text,
                'position' => $asset->position,
            ]),
            'comments' => CommentResource::collection($this->comments),
            'versions' => $this->versions->map(fn ($version) => [
                'id' => $version->id,
                'number' => $version->version_number,
                'notes' => $version->notes,
                'status' => $version->status,
                'created_at' => $version->created_at->toISOString(),
            ]),
            'approval_history' => $this->approvalActions->map(fn ($action) => [
                'id' => $action->id,
                'action' => $action->action,
                'actor_name' => $action->actor_name,
                'comment' => $action->comment,
                'priority' => $action->priority,
                'created_at' => $action->created_at->toISOString(),
            ]),
        ];
    }
}
