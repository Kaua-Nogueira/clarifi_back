<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentAsset extends Model
{
    protected $fillable = ['content_id', 'kind', 'url', 'alt_text', 'position'];
    public function content(): BelongsTo { return $this->belongsTo(Content::class); }
}
