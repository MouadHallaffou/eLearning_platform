<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'url'           => $this->url,
            'created_at'    => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'update_at'     => Carbon::parse($this->update_at)->format('Y-m-d H:i:s'),
        ];
    }
}
