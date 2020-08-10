<?php

namespace App\Http\Resources\Zone;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortZoneResource extends JsonResource
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
        return [
            'id' => $this->format($this->id, 'integer'),
            'name' => $this->format($this->name,'string'),
            'banner' => $this->format($banner)
        ];
    }
}
