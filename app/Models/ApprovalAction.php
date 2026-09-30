<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalAction extends Model
{
    protected $fillable = ['content_id', 'action', 'actor_name', 'comment', 'priority'];
    public function content(): BelongsTo { return $this->belongsTo(Content::class); }
}
