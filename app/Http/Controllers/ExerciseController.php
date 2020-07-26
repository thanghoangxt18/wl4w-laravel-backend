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

    public function createOrUpdateExercise(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'image' => 'required|file',
            'thumb_image' => 'required|file',
            'description' => 'required|string',
            'video' => 'required|string',
            'duration' => 'required|int',
            'tts_guide' => 'required|string',
            'met' => 'required|int'
        ]);
        $id = $request->id ? $request->id : '';

        try {
            ExerciseService::saveToExerciseTable(
                $id,
                $request->name,
                $request->file('image'),
                $request->file('thumb_image'),
                $request->description,
                $request->video,
                $request->duration,
                $request->tts_guide,
                $request->met
            );
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function getExerciseByKeyword(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required|string'
        ]);
        $exerciseListSearched = Exercise::query()
            ->where('name', 'LIKE', '%' . $request->keyword . "%")
            ->paginate(10);

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
            return $this->successResponse(
                ['message'=>'Delete successfully'],
                'Successfull'
            );
        } catch (Exception $e){
            return $this->errorResponse(
                $e->getMessage(),
                402
            );
        }
    }

}
