<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveContentRequest;
use App\Http\Resources\ContentDetailResource;
use App\Models\Content;
use App\Services\AdminContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminContentController extends Controller
{
    public function __construct(private readonly AdminContentService $service) {}

    public function index(Request $request): JsonResponse
    {
        $contents = Content::query()
            ->with(['client', 'assets'])
            ->withCount(['comments as open_comments_count' => fn ($query) => $query->where('status', 'open')])
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(fn ($nested) => $nested->where('title', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($client) => $client->where('name', 'like', "%{$search}%")));
            })
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->when($request->integer('client_id'), fn ($query, $clientId) => $query->where('client_id', $clientId))
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (Content $content) => [
                'id' => $content->id,
                'slug' => $content->slug,
                'title' => $content->title,
                'client_id' => $content->client_id,
                'client' => $content->client->name,
                'channel' => $content->channel,
                'type' => $content->type,
                'status' => $content->status,
                'publication_date' => $content->publication_date->toISOString(),
                'thumbnail' => $content->assets->first()?->url,
                'assets_count' => $content->assets->count(),
                'open_comments_count' => $content->open_comments_count,
                'updated_at' => $content->updated_at->toISOString(),
            ]);

        return response()->json(['data' => $contents]);
    }

    public function show(Content $content): ContentDetailResource
    {
        return new ContentDetailResource($content->load(['client', 'assets', 'versions', 'comments.replies', 'comments.asset', 'approvalActions']));
    }

    public function store(SaveContentRequest $request): ContentDetailResource
    {
        $content = $this->service->create($request->validated());
        return new ContentDetailResource($content->load(['client', 'assets', 'versions', 'comments.replies', 'approvalActions']));
    }

    public function update(SaveContentRequest $request, Content $content): ContentDetailResource
    {
        $content = $this->service->update($content, $request->validated());
        return new ContentDetailResource($content->load(['client', 'assets', 'versions', 'comments.replies', 'approvalActions']));
    }

    public function destroy(Content $content): JsonResponse
    {
        $content->delete();
        return response()->json(status: 204);
    }
}
