<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Item\ShortItemResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortestCourseResource extends JsonResource
{
    use FormatResponse;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name),
            'banner' => $this->format($this->banner),
        ];
    }
}
