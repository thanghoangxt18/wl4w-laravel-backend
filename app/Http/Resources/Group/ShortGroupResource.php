<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\Course\ShortCourseResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortGroupResource extends JsonResource
{
    use FormatResponse;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    use FormatResponse;

    public function toArray($request)
    {
       $exercise = $this->exercise ;
       $total_exercise= count($exercise);
       $total_time = 0;
       foreach ($exercise as $item)
           $total_time+= $item->reps * $item->time_per_rep;
       return [
           'group_id'=>$this->format($this->id,'integer'),
           'name'=>$this->format($this->name),
           'id' => $this->whenPivotLoaded('groups_items',
               function () {
                   return $this->pivot->id;
               }) ?: 0,
           'banner'=>$this->format($this->banner),
           'description'=>$this->format($this->description),
           'total_time'=>$total_time,
           'total_exercise'=>$total_exercise
       ];
    }
}
