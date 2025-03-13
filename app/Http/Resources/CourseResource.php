<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'content'       => $this->content,
            'video'         => $this->video,
            'cover'         => $this->cover,
            'duration'      => $this->duration,
            'level'         => $this->level,
            'category_name' => $this->category->name ?? null,
            'tags'          => $this->tags->pluck('name')->implode(', '), 
            'created_at'    => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'update_at'     => Carbon::parse($this->update_at)->format('Y-m-d H:i:s'),
        ];
    }
}
