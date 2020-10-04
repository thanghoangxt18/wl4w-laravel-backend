<?php

namespace App\Http\Resources\Music;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class MusicResource extends JsonResource
{
    use FormatResponse;

    public function toArray($request)
    {
        $url = $this->url ? config('constants.SERVER_MEDIA_URL') . $this->url : '';
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name),
            'url' => $this->format($url),
            'bpm' => $this->format($this->bpm, 'integer'),
            'tag' => $this->format($this->tag)
        ];
    }
}
