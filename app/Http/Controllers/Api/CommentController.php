<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Content;
use App\Services\ContentWorkflowService;

class CommentController extends Controller
{
    public function __construct(private readonly ContentWorkflowService $workflow) {}

    public function store(StoreCommentRequest $request, Content $content): CommentResource
    {
        $data = $request->validated();

        if (isset($data['content_asset_id']) && ! $content->assets()->whereKey($data['content_asset_id'])->exists()) {
            abort(422, 'A peça selecionada não pertence a este conteúdo.');
        }

        return new CommentResource($this->workflow->addComment($content, $data));
    }

    public function resolve(Comment $comment): CommentResource
    {
        return new CommentResource($this->workflow->toggleResolved($comment));
    }
}
