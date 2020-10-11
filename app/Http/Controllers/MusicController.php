<?php

namespace App\Http\Controllers;

use App\Http\Resources\Music\MusicCollection;
use App\Models\Music;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class MusicController extends Controller
{
    use ApiResponse;
    const STORAGE_PATH_MUSIC = '/images/music';


    // get music
    public function getMusic(Request $request)
    {
        $perPage = $request->per_page ? (int)$request->per_page : 5;
        $keyword = $request->input('keyword');
        $bpm = $request->input('bpm');
        $bpmRange = $request->input('bpm_range');
        $tag = $request->input('tag');
        if ($keyword !== '' || $bpm !== '' || $tag !== '') {
            $condition = [];
            if ($keyword) {
                $condition[] = ['name', 'LIKE', '%'.$keyword.'%'];
            }
            if ($bpm) {
                $condition[] = ['bpm', '=', $bpm];
            }
            if ($tag) {
                $condition[] = ['tag', 'LIKE', '%'.$tag.'%'];
            }
            if ($bpmRange && is_array($bpmRange)) {
                if (isset($bpmRange['start'])) {
                    $condition[] = ['bpm', '>=', $bpmRange['start']];
                }
                if (isset($bpmRange['end'])) {
                    $condition[] = ['bpm', '<=', $bpmRange['end']];
                }
            }
            $musics = Music::query()
                ->where($condition)
                ->paginate($perPage);
        } else {
            $musics = Music::paginate($perPage);
        }
        $result = new MusicCollection($musics);
        return $this->successResponse($result, 'Success', 200);
    }

    public function createMusic(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'bpm' => 'required|integer',
            'url' => 'required'
        ]);
        $music = new Music();
        $music->name = $request->name;
        $music->bpm = $request->bpm;
        $music->tag = strtolower($request->tag);
        $url = $request->url;
        $urlPath = Storage::put(self::STORAGE_PATH_MUSIC, $url);
        $ext = $url->getClientOriginalExtension();
        $fileName = pathinfo($url->getClientOriginalName(), PATHINFO_FILENAME);
        $mainFilename = $fileName . Str::random(6) . date('Y-m-d-h-i-s');
        $urlPathNew = self::STORAGE_PATH_MUSIC . '/' . $mainFilename . "." . $ext;
        Storage::move($urlPath, $urlPathNew);
        $urlPathNew = 'storage' . $urlPathNew;
        $music->url = $urlPathNew;
        try {
            $music->save();
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => $e->getMessage()]
            );
        }
    }


    public function updateMusic(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|string',
        ]);

        $music = Music::findOrFail($request->id);
        $music->name = $request->name ?? $music->name;
        $music->bpm = $request->bpm ?? $music->bpm;
        if ($request->url) {
            $url = $request->url;
            $urlPath = Storage::put(self::STORAGE_PATH_MUSIC, $url);
            $ext = $url->getClientOriginalExtension();
            $fileName = pathinfo($url->getClientOriginalName(), PATHINFO_FILENAME);
            $mainFilename = $fileName . Str::random(6) . date('Y-m-d-h-i-s');
            $urlPathNew = self::STORAGE_PATH_MUSIC . '/' . $mainFilename . "." . $ext;
            Storage::move($urlPath, $urlPathNew);
            $urlPathNew = 'storage' . $urlPathNew;
        }
        $music->url = isset($urlPathNew) ? $urlPathNew : $music->url;
        try {
            $music->save();
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => $e->getMessage()]
            );
        }
    }


    public function deleteMusic(Request $request)
    {
        $this->validate($request, [
            'id' => 'integer|required'
        ]);
        try {
            Music::where('id', '=', $request->id)->delete();
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
