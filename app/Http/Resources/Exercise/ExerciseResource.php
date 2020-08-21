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
            'image' => $this->format($this->image),
            'thumb_image' => $this->format($this->thumb_image),
            'description' => $this->format($this->description),
            'video' => $this->format($this->video),
            'type' => $this->format($this->type, 'integer'),
            'reps' => $this->format($this->reps, 'integer'),
            'time_per_rep' => $this->format($this->time_per_rep, 'integer'),
            'tts_guide' => $this->format($this->tts_guide),
            'met' => $this->format($this->met, 'integer'),
        ];
    }
}
