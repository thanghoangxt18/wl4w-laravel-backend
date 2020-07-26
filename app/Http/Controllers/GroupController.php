<?php

namespace App\Http\Controllers;

use App\Http\Resources\Group\GroupCollection;
use App\Models\Group;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    use ApiResponse;

    public function createNewGroup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'banner' => 'required|file',
            'description' => 'required|string',
            'listExerciseId' => 'required|array'
        ]);
        $group = new Group();
        $group->name = $request->name;
        $bannerPath = Storage::put('images/group', $request->banner);
        $group->banner = $bannerPath;
        $group->description = $request->description;
        $group->save();
        $listExerciseId = $request->listExerciseId;
        $order = 0;
        try {
            foreach ($listExerciseId as $exerciseId) {
                DB::table('exercise_groups')
                    ->insert(
                        ['exercise_id' => $exerciseId, 'group_id' => $group->id, 'order' => ++$order]
                    );
            };
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function getGroupByKeyword(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required|string'
        ]);

        $groupListSearched = Group::query()
            ->where('name', 'LIKE', '%' . $request->keyword . "%")
            ->paginate(10);


        $result = new GroupCollection($groupListSearched);
        return $this->successResponse($result, 'Success', 200);
    }

    public function updateGroup(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|string',
            'name' => 'required|string',
            'banner' => 'required|file',
            'description' => 'required|string',
            'listExerciseId' => 'required|array'
        ]);

        $group = new Group();
        $group->id = $request->id;
        $group->name = $request->name;
        $bannerPath = Storage::put('images/group', $request->banner);
        $group->banner = $bannerPath;
        $group->description = $request->description;
        $listExerciseId = $request->listExerciseId;
        $order = 0;
        try {
            DB::table('exercise_groups')->where('group_id', '=', $group->id)->delete();
            foreach ($listExerciseId as $exerciseId) {
                DB::table('exercise_groups')
                    ->insert(
                        ['exercise_id' => $exerciseId, 'group_id' => $group->id, 'order' => ++$order]
                    );
            };
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function deleteGroup(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'string|required'
        ]);
        try {
            DB::table('exercise_group')->where('group_id', '=', $request->group_id);
            Group::where('id','=',$request->group_id)->delete();
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

}
