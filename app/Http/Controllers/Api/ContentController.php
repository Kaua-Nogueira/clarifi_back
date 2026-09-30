<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ContentRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveContentRequest;
use App\Http\Requests\RequestChangesRequest;
use App\Http\Resources\ContentDetailResource;
use App\Http\Resources\ContentListResource;
use App\Models\Content;
use App\Services\ContentWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContentController extends Controller
{
    public function __construct(
        private readonly ContentRepositoryInterface $contents,
        private readonly ContentWorkflowService $workflow,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return ContentListResource::collection($this->contents->all($request->string('month')->toString() ?: null));
    }

    public function show(Content $content): ContentDetailResource
    {
        return new ContentDetailResource($this->contents->findWithDetails($content));
    }

    public function approve(ApproveContentRequest $request, Content $content): ContentDetailResource
    {
        $this->workflow->approve($content, $request->validated());
        return new ContentDetailResource($this->contents->findWithDetails($content));
    }

    public function requestChanges(RequestChangesRequest $request, Content $content): ContentDetailResource
    {
        $this->workflow->requestChanges($content, $request->validated());
        return new ContentDetailResource($this->contents->findWithDetails($content));
    }
}
