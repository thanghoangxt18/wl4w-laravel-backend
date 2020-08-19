<?php

namespace App\Http\Resources\DiscoverCourse;

use App\Http\Resources\Item\ShortItemResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscoverCourseResource extends JsonResource
{
    use FormatResponse;

    public function toArray($request)
    {
        // discover course has only one item
        $items = ShortItemResource::collection($this->items);
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name),
            'layout_type' => $this->format($this->layout_type),
            'items' => $items,
        ];
    }
}
