<?php

namespace App\Contracts;

use App\Models\Content;
use Illuminate\Database\Eloquent\Collection;

interface ContentRepositoryInterface
{
    public function all(?string $month = null): Collection;
    public function findWithDetails(Content $content): Content;
    public function recent(int $limit = 5): Collection;
    public function pending(int $limit = 4): Collection;
    public function upcoming(int $limit = 5): Collection;
    public function statusCounts(): array;
}
