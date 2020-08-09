<?php

namespace App\Http\Resources\Zone;

use App\Http\Resources\Exercise\ExerciseResource;
use App\Http\Resources\Exercise\ShortestExerciseResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ZoneResource extends JsonResource
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
        $banner = $this->banner ? config('constants.SERVER_MEDIA_URL') . $this->banner : '';
        $exercises = ExerciseResource::collection($this->exercise);
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name,'string'),
            'banner' => $this->format($banner),
            'exercises' => $exercises
        ];
    }
}
