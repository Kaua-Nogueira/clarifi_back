<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    protected $fillable = [
        'client_id', 'title', 'slug', 'channel', 'type', 'status',
        'publication_date', 'responsible', 'objective', 'editorial_line',
        'cta', 'audience', 'caption', 'agency_notes',
    ];

    protected function casts(): array
    {
        return ['publication_date' => 'datetime'];
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function assets(): HasMany { return $this->hasMany(ContentAsset::class)->orderBy('position'); }
    public function comments(): HasMany { return $this->hasMany(Comment::class)->whereNull('parent_id')->latest(); }
    public function versions(): HasMany { return $this->hasMany(ContentVersion::class)->orderByDesc('version_number'); }
    public function approvalActions(): HasMany { return $this->hasMany(ApprovalAction::class)->latest(); }
}
