<?php
namespace App\Http\Resources\DiscoverCourse;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DiscoverCourseCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'courses' => DiscoverCourseResource::collection($this->collection),
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
