<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Badge extends Model
{
    protected $fillable = ['name', 'description', 'type', 'image_url'];

    public function rules(): HasMany
    {
        return $this->hasMany(BadgeRule::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
