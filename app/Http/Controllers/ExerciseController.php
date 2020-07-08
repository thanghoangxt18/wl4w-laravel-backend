<?php

namespace App\Http\Controllers;

use App\Http\Resources\Exercise\ExerciseResource;
use App\Models\Exercise;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    use ApiResponse;

    public function getDetailExercise(Request $request) {
        $exercise = Exercise::findOrFail($request->exercise_id);
        return $this->successResponse(new ExerciseResource($exercise), 'success');
    }

}
