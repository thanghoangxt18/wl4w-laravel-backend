<?php

namespace App\Http\Resources\Exercise;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    use FormatResponse;

    public function toArray($request)
    {
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name),
            'exercise_group_id' => $this->whenPivotLoaded('exercise_groups',
                function () {
                    return $this->pivot->id;
                }) ?: 0,
            'image' => $this->format($this->image),
            'description' => $this->format($this->description),
            'video' => $this->format($this->video),
            'type' => $this->format($this->type, 'string'),
            'default_duration' => $this->format($this->default_duration, 'integer'),
            'reps' => $this->format($this->reps, 'integer'),
            'time_per_rep' => $this->format($this->time_per_rep, 'double'),
            'tts_guide' => $this->format($this->tts_guide),
            'met' => $this->format($this->met, 'double'),
        ];
    }
}
