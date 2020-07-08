<?php

namespace App\Http\Resources\Zone;

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
        $banner = $this->banner ? config('constans.SERVER_MEDIA_URL') . $this->banner : '';
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->id,'string'),
            'banner' => $this->format($banner)
        ];
    }
}
