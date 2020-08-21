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

    public function getGroupList(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        $groups = Group::paginate($per_page);
        $result = new GroupCollection($groups);
        return $this->successResponse($result, 'success');
    }

    public function createNewGroup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'banner' => 'required|file',
            'description' => 'required|string',
        ]);
        $group = new Group();
        $group->name = $request->name;
        $bannerPath = Storage::put('images/group', $request->banner);
        $group->banner = $bannerPath;
        $group->description = $request->description;
        try {
            $group->save();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function getGroupByKeyword(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        if ($request->keyword !== '') {
            $groupListSearched = Group::query()
                ->where('name', 'LIKE', '%' . $request->keyword . "%")
                ->paginate($per_page);
        } else {
            $groupListSearched = Group::paginate($per_page);
        }
        $result = new GroupCollection($groupListSearched);
        return $this->successResponse($result, 'Success', 200);
    }

    public function updateGroup(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $group_id = $request->id;
        $group = Group::find($group_id);
        $group->name = $request->name;
        $bannerPath = null;
        if ($request->banner) {
            $bannerPath = Storage::put('images/group', $request->banner);
        }
        $group->banner = $bannerPath ? $bannerPath : $group->banner;
        $group->description = $request->description;
        try {
            $group->save();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function addNewExerciseToGroup(Request $request)
    {
        $this->validate($request, [
            'exercise_id' => 'required|int',
            'group_id' => 'required|int',
            'order' => 'int'
        ]);
        try {
            DB::table('exercise_groups')->insert(
                ['exercise_id' => $request->exercise_id,
                    'group_id' => $request->group_id,
                    'order' => $request->order ? (int)$request->order : 1]);
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function deleteExerciseOfGroup(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'required|int',
            'exercise_id' => 'required|int'
        ]);
        try {
            DB::table('exercise_groups')
                ->where('exercise_id', '=', $request->exercise_id)
                ->where('group_id', '=', $request->group_id)
                ->delete();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, []
            );
        }
    }

    public function deleteGroup(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'int|required'
        ]);
        try {
            DB::table('exercise_group')->where('group_id', '=', $request->group_id)->delete();
            Group::where('id', '=', $request->group_id)->delete();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function updateOrderOfExercise(Request $request)
    {
        $this->validate($request, [
            'group_list_order' => 'required'
        ]);
        $groupListOrder = $request->input('group_list_order');
        $h =json_encode($groupListOrder);
        dd($h);
        dd($h->group_id);
        $groupId = (int)$groupListOrder->group_id;
        $exerciseOrderList = (array)$groupListOrder->exercise_order;
        try {
            foreach ($exerciseOrderList as $item) {
                DB::table('exercise_group')
                    ->where('group_id', '=', $groupId)
                    ->where('exercise_id', '=', (int)$item->id)
                    ->delete();

                DB::table('exercise_group')
                    ->insert(['exercise_id' => (int)$item->id, 'group_id' => $groupId, 'order' => (int)$item->index]);
            }
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message'=>$e->getMessage()]);
        }
    }
}
