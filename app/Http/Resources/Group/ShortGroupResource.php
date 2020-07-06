<?php

namespace App\Http\Resources\Group;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    use FormatResponse;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
