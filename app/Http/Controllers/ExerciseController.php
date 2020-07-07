<?php

namespace App\Http\Controllers;

use App\Http\Resources\Exercise\ExerciseResource;
use App\Models\Exercise;
use App\Traits\ApiResponse;

class ExerciseController extends Controller
{
    use ApiResponse;

    public function getDetailExercise($id) {
        $exercise = Exercise::findOrFail($id);
        return $this->successResponse(new ExerciseResource($exercise), 'success');
    }


}
