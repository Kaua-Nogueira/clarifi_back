<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'content_id', 'content_asset_id', 'parent_id', 'author_name', 'author_role',
        'body', 'type', 'status', 'position_x', 'position_y',
    ];

    protected function casts(): array
    {
        return ['position_x' => 'float', 'position_y' => 'float'];
    }

    public function content(): BelongsTo { return $this->belongsTo(Content::class); }
    public function asset(): BelongsTo { return $this->belongsTo(ContentAsset::class, 'content_asset_id'); }
    public function replies(): HasMany { return $this->hasMany(Comment::class, 'parent_id')->oldest(); }
}
