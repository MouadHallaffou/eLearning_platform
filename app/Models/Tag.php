<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    protected $model = Tag::class;

    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name',
    ];
}
