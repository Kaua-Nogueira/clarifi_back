<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentVersion extends Model
{
    protected $fillable = ['content_id', 'version_number', 'notes', 'status'];
    public function content(): BelongsTo { return $this->belongsTo(Content::class); }
}
