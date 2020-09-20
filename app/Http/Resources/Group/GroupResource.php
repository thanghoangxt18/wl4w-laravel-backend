<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\Exercise\ExerciseResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
        $exercises = ExerciseResource::collection($this->exercise);
        $total_exercise = count($exercises);
        $total_time = 0;
        foreach ($exercises as $item)
            $total_time += (int)$item->reps * (int)$item->time_per_rep;
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name),
            'group_item_id' => $this->whenPivotLoaded('groups_items',
                function () {
                    return $this->pivot->id;
                }) ?: 0,
            'banner' => $this->format($this->banner),
            'description' => $this->format($this->description),
            'total_time' => $total_time,
            'total_exercise' => $total_exercise,
            'exercises' => $exercises
        ];
    }
}
