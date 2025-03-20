<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'url',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
}
