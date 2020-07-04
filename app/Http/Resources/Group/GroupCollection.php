<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'groups' => ShortGroupResource::collection($this->collection),
            'pagination'=>[
                'total'=>$this->total(),
                'count'=>$this->count(),
                'per_page'=> $this->perpage(),
                'current_page'=>$this->currentPage(),
                'total_pages'=>$this->lastPage()
            ]
        ];
    }
}
