<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = ['name', 'contact_name', 'email', 'segment', 'status'];

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
