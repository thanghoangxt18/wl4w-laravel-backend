<?php


namespace App\Http\Service;


use App\Models\Exercise;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExerciseService
{
    use ApiResponse;

    const STORAGE_PATH_EXERCISE = '/images/exercise';

    public static function saveToExerciseTable($id = 0, $name, $image = null, $thumb_image = null, $description, $video, $type = "by_time", $default_duration = 0, $reps = 0, $time_per_rep = 0, $tts_guide, $met)
    {
        if ($image) {
            $imagePath = Storage::put(self::STORAGE_PATH_EXERCISE, $image);
            $ext = $image->getClientOriginalExtension();
            $fileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $mainFilename = $fileName . Str::random(6) . date('Y-m-d-h-i-s');
            $imagePathNew = self::STORAGE_PATH_EXERCISE . '/' . $mainFilename . "." . $ext;
            Storage::move($imagePath, $imagePathNew);
            $imagePathNew = 'storage' . $imagePathNew;
        }
        if ($thumb_image) {
            $thumb_imagePath = Storage::put(self::STORAGE_PATH_EXERCISE, $thumb_image);
        } else {
            $thumb_imagePath = "";
        }

        if ($id == 0) {
            $exercise = new Exercise();
            self::setDataToExercise($exercise, $name, $imagePathNew, $thumb_imagePath, $description, $video, $type,$default_duration, $reps, $time_per_rep, $tts_guide, $met);
            $exercise->save();
        } else {
            $exercise = Exercise::find($id);
            if (!$image) $imagePathNew = $exercise->image;
            self::setDataToExercise($exercise, $name, $imagePathNew, $thumb_imagePath, $description, $video, $type,$default_duration, $reps, $time_per_rep, $tts_guide, $met);
            $exercise->save();
        }
    }

    public static function setDataToExercise($exercise, $name, $imagePath, $thumb_imagePath, $description, $video, $type,$default_duration, $reps, $time_per_rep, $tts_guide, $met)
    {
        $exercise->name = $name;
        $exercise->image = $imagePath;
        $exercise->thumb_image = $thumb_imagePath;
        $exercise->description = $description;
        $exercise->video = $video;
        $exercise->type = $type;
        $exercise->default_duration = $default_duration;
        $exercise->reps = $reps;
        $exercise->time_per_rep = $time_per_rep;
        $exercise->tts_guide = $tts_guide;
        $exercise->met = $met;
    }


}
