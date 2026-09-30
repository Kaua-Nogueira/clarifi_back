<?php

namespace App\Services;

use App\Contracts\ContentRepositoryInterface;
use App\Http\Resources\ContentListResource;

class DashboardService
{
    public function __construct(private readonly ContentRepositoryInterface $contents) {}

    public function getOverview(): array
    {
        return [
            'summary' => $this->contents->statusCounts(),
            'pending' => ContentListResource::collection($this->contents->pending())->resolve(),
            'upcoming' => ContentListResource::collection($this->contents->upcoming())->resolve(),
            'recent' => ContentListResource::collection($this->contents->recent())->resolve(),
        ];
    }
}
