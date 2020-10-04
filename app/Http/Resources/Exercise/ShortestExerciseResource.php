<?php

namespace App\Http\Resources\Exercise;

use Illuminate\Http\Resources\Json\JsonResource;

class ShortestExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'exercise_id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name),
            'id' => $this->whenPivotLoaded('exercise_groups',
                function () {
                    return $this->pivot->id;
                }) ? : 0,
        ];
    }
}
