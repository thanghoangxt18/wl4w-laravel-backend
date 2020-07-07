<?php

namespace App\Http\Resources\Exercise;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
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
        //$thumbImage = $this->thumb_image ? config('constants.SERVER_MEDIA_URL').$this->thumb_img : '';
        return [
            'id'=>$this->format($this->id,'integer'),
            'name' => $this->format($this->name),
            'description' => $this->format($this->description),
            'tts_guide' => $this->format($this->tts_guide),
            'image' => $this->format($this->image),
            'thumb_image' => $this->format($this->thumb_image),
            'video' => $this->format($this->video),
            'default_duration' => $this->format($this->default_duration, 'integer'),

        ];
    }
}
