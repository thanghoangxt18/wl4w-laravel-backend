<?php
namespace App\Http\Resources\Exercise;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExerciseCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'exercises' => ExerciseResource::collection($this->collection),
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage()
            ]
        ];
    }

}
