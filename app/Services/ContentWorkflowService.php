<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Content;
use Illuminate\Support\Facades\DB;

class ContentWorkflowService
{
    public function approve(Content $content, array $data): Content
    {
        return DB::transaction(function () use ($content, $data) {
            $content->update(['status' => 'approved']);
            $content->approvalActions()->create([
                'action' => 'approved',
                'actor_name' => $data['actor_name'] ?? 'Marina Costa',
                'comment' => $data['comment'] ?? null,
            ]);

            return $content;
        });
    }

    public function requestChanges(Content $content, array $data): Content
    {
        return DB::transaction(function () use ($content, $data) {
            $content->update(['status' => 'changes_requested']);
            $content->approvalActions()->create([
                'action' => 'changes_requested',
                'actor_name' => $data['actor_name'] ?? 'Marina Costa',
                'comment' => $data['comment'],
                'priority' => $data['priority'] ?? 'normal',
            ]);
            $content->comments()->create([
                'author_name' => $data['actor_name'] ?? 'Marina Costa',
                'author_role' => 'Cliente',
                'body' => $data['comment'],
                'type' => 'general',
                'status' => 'open',
            ]);

            return $content;
        });
    }

    public function addComment(Content $content, array $data): Comment
    {
        return $content->comments()->create([
            ...$data,
            'author_name' => $data['author_name'] ?? 'Marina Costa',
            'author_role' => $data['author_role'] ?? 'Cliente',
            'type' => isset($data['content_asset_id']) ? 'specific' : 'general',
            'status' => 'open',
        ])->load('replies', 'asset');
    }

    public function toggleResolved(Comment $comment): Comment
    {
        $comment->update(['status' => $comment->status === 'resolved' ? 'open' : 'resolved']);
        return $comment->refresh()->load('replies', 'asset');
    }
}
