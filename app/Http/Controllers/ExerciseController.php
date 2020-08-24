<?php

namespace App\Http\Controllers;

use App\Http\Resources\Exercise\ExerciseCollection;
use App\Http\Resources\Exercise\ExerciseResource;
use App\Http\Service\ExerciseService;
use App\Models\Exercise;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use PHPUnit\Exception;

class ExerciseController extends Controller
{
    use ApiResponse;

    public function getExerciseList(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        $exercies = Exercise::paginate($per_page);
        $result = new ExerciseCollection($exercies);
        return $this->successResponse($result, 'success');
    }


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
            'description' => 'required|string',
            'video' => 'required|string',
            'type' => 'string|required',
            'reps' => 'int',
            'time_per_rep' => 'int',
            'tts_guide' => 'required|string',
            'met' => 'required|required|regex:/^\d+(\.\d{1,4})?$/'
        ]);

        try {
            ExerciseService::saveToExerciseTable(
                $id = 0,
                $request->name,
                $request->file('image'),
                $request->file('thumb_image'),
                $request->description,
                $request->video,
                $request->type,
                $request->reps,
                $request->time_per_rep,
                $request->tts_guide,
                $request->met
            );
            return $this->successResponse('', 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => $e->getMessage()]
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
            'type' => 'string',
            'reps' => 'int',
            'time_per_rep' => 'int',
            'tts_guide' => 'required|string',
            'met' => 'required|regex:/^\d+(\.\d{1,4})?$/'
        ]);
        try {
            ExerciseService::saveToExerciseTable(
                $request->id,
                $request->name,
                $request->file('image'),
                $request->file('thumb_image'),
                $request->description,
                $request->video,
                $request->type,
                $request->reps,
                $request->time_per_rep,
                $request->tts_guide,
                $request->met
            );
            return $this->successResponse('', 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => $e->getMessage()]
            );
        }
    }

    public function getExerciseByKeyword(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        if ($request->keyword) {
            $exerciseListSearched = Exercise::query()
                ->where('name', 'LIKE', '%' . $request->keyword . "%")
                ->paginate($per_page);
        } else {
            $exerciseListSearched = Exercise::paginate($per_page);
        }

        $result = new ExerciseCollection($exerciseListSearched);
        return $this->successResponse($result, 'Success', 200);
    }

    public function deleteExercise(Request $request)
    {
        try {
            $this->validate($request, [
                'exercise_id' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 402);
        }
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
