<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'asset_id' => $this->content_asset_id,
            'parent_id' => $this->parent_id,
            'author_name' => $this->author_name,
            'author_role' => $this->author_role,
            'body' => $this->body,
            'type' => $this->type,
            'status' => $this->status,
            'position_x' => $this->position_x,
            'position_y' => $this->position_y,
            'created_at' => $this->created_at->toISOString(),
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
