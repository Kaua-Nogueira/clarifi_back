<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Content;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $statusCounts = Content::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $recent = Content::with(['client', 'assets'])->latest('updated_at')->limit(6)->get();
        $upcoming = Content::with(['client', 'assets'])->where('publication_date', '>=', now()->startOfDay())->orderBy('publication_date')->limit(5)->get();

        $mapContent = fn (Content $content) => [
            'id' => $content->id,
            'slug' => $content->slug,
            'title' => $content->title,
            'client' => $content->client->name,
            'status' => $content->status,
            'channel' => $content->channel,
            'type' => $content->type,
            'publication_date' => $content->publication_date->toISOString(),
            'thumbnail' => $content->assets->first()?->url,
        ];

        return response()->json(['data' => [
            'summary' => [
                'clients' => Client::where('status', 'active')->count(),
                'contents' => Content::count(),
                'pending' => (int) ($statusCounts['pending'] ?? 0),
                'changes_requested' => (int) ($statusCounts['changes_requested'] ?? 0),
                'open_comments' => Comment::where('status', 'open')->count(),
            ],
            'status_counts' => $statusCounts,
            'recent' => $recent->map($mapContent),
            'upcoming' => $upcoming->map($mapContent),
        ]]);
    }
}
