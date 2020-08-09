<?php

namespace App\Http\Controllers;

use App\Http\Resources\Exercise\ExerciseCollection;
use App\Http\Resources\Exercise\ExerciseResource;
use App\Http\Service\ExerciseService;
use App\Models\Exercise;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExerciseController extends Controller
{
    use ApiResponse;

    public function getDetailExercise(Request $request)
    {
        $this->validate($request, [
            'exercise_id' => 'required|string'
        ]);
        $exercise = Exercise::findOrFail($request->exercise_id);
        return $this->successResponse(new ExerciseResource($exercise), 'success');
    }

    public function createNewExercise(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'image' => 'required',
            'thumb_image' => 'required',
            'description' => 'required|string',
            'video' => 'required|string',
            'duration' => 'required|int',
            'tts_guide' => 'required|string',
            'met' => 'required|int'
        ]);

        try {
            ExerciseService::saveToExerciseTable(
                $id=0,
                $request->name,
                $request->file('image'),
                $request->file('thumb_image'),
                $request->description,
                $request->video,
                $request->duration,
                $request->tts_guide,
                $request->met
            );
            return $this->successResponse('','Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Crreate failed']
            );
        }
    }
    public function updateExercise(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
            'name' => 'required|string',
            'description' => 'required|string',
            'video' => 'required|string',
            'duration' => 'required|int',
            'tts_guide' => 'required|string',
            'met' => 'required|int'
        ]);
        try {
            ExerciseService::saveToExerciseTable(
                $request->id,
                $request->name,
                $request->file('image'),
                $request->file('thumb_image'),
                $request->description,
                $request->video,
                $request->duration,
                $request->tts_guide,
                $request->met
            );
            return $this->successResponse('','Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Crreate failed']
            );
        }
    }

    public function getExerciseByKeyword(Request $request)
    {
        if($request->keyword){
            $exerciseListSearched = Exercise::query()
                ->where('name', 'LIKE', '%' . $request->keyword . "%")
                ->paginate(10);
        } else {
            $exerciseListSearched = Exercise::paginate(10);
        }

        $result = new ExerciseCollection($exerciseListSearched);
        return $this->successResponse($result, 'Success', 200);
    }

    public function deleteExercise(Request $request)
    {
        $this->validate($request, [
            'exercise_id' => 'required|string'
        ]);
        $exercise = Exercise::findOrFail($request->exercise_id);
        try {
            Storage::delete($exercise->image);
            Storage::delete($exercise->thumb_image);
            $exercise->delete();
            return $this->successResponse([], 'Successfull');
        } catch (Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                402
            );
        }
    }

}
