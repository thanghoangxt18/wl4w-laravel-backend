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
        $exercise = ExerciseResource::collection($this->exercise);
        $total_exercise = count($exercise);
        $total_time = 0;
        foreach ($exercise as $item)
            $total_time += $item->duration;
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name),
            'banner' => $this->format($this->banner),
            'description' => $this->format($this->description),
            'total_time' => $total_time,
            'total_exercise' => $total_exercise,
            'exercises' => $exercise
        ];
    }
}
