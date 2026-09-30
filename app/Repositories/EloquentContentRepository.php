<?php

namespace App\Repositories;

use App\Contracts\ContentRepositoryInterface;
use App\Models\Content;
use Illuminate\Database\Eloquent\Collection;

class EloquentContentRepository implements ContentRepositoryInterface
{
    public function all(?string $month = null): Collection
    {
        return Content::query()
            ->with(['client', 'assets'])
            ->when($month, fn ($query) => $query->whereYear('publication_date', substr($month, 0, 4))
                ->whereMonth('publication_date', substr($month, 5, 2)))
            ->orderBy('publication_date')
            ->get();
    }

    public function findWithDetails(Content $content): Content
    {
        return $content->load([
            'client', 'assets', 'versions',
            'comments.replies', 'comments.asset', 'approvalActions',
        ]);
    }

    public function recent(int $limit = 5): Collection
    {
        return Content::with(['client', 'assets'])->latest('updated_at')->limit($limit)->get();
    }

    public function pending(int $limit = 4): Collection
    {
        return Content::with(['client', 'assets'])
            ->whereIn('status', ['pending', 'in_review'])
            ->orderBy('publication_date')->limit($limit)->get();
    }

    public function upcoming(int $limit = 5): Collection
    {
        return Content::with(['client', 'assets'])
            ->where('publication_date', '>=', now()->startOfDay())
            ->orderBy('publication_date')->limit($limit)->get();
    }

    public function statusCounts(): array
    {
        $counts = Content::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return [
            'pending' => (int) ($counts['pending'] ?? 0),
            'approved' => (int) ($counts['approved'] ?? 0),
            'changes_requested' => (int) ($counts['changes_requested'] ?? 0),
            'in_review' => (int) ($counts['in_review'] ?? 0),
            'published' => (int) ($counts['published'] ?? 0),
        ];
    }
}
