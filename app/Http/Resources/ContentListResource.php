<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'client' => $this->client->name,
            'channel' => $this->channel,
            'type' => $this->type,
            'status' => $this->status,
            'publication_date' => $this->publication_date->toISOString(),
            'thumbnail' => $this->assets->first()?->url,
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
