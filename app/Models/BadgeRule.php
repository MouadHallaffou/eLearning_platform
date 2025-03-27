<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BadgeRule extends Model
{
    protected $fillable = ['operator', 'condition', 'value'];

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }
}
