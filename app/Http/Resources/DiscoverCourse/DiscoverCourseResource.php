<?php

namespace App\Http\Resources\DiscoverCourse;

use App\Http\Resources\Group\ShortGroupResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscoverCourseResource extends JsonResource
{
    use FormatResponse;

    public function toArray($request)
    {
        // discover course has only one item
        $firstItem = $this->items->first();
        $group = $firstItem ? ShortGroupResource::collection($firstItem->groups) : [];
        return [
            'id' => $this->format($this->id, 'integer'),
            'title' => $this->format($this->title),
            'layout_type' => $this->format($this->layout_type),
            'group_workouts' => $this->format($group, 'array'),
        ];
    }
}
