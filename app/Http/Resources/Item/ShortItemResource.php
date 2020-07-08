<?php

namespace App\Http\Resources\Item;

use App\Http\Resources\Group\ShortGroupResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortItemResource extends JsonResource
{
    use FormatResponse;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $groups = ShortGroupResource::collection($this->groups);
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name),
            'banner' => $this->format($this->banner),
            'group_workouts' => $groups,
        ];
    }
}
