<?php


namespace App\Http\Service;


use App\Models\Exercise;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;

class ExerciseService
{
    use ApiResponse;

    public static function saveToExerciseTable($id = 0, $name, $image = null, $thumb_image = null, $description, $video, $type = "by_time", $reps = 0, $time_per_rep = 0, $tts_guide, $met)
    {
        if ($image) $imagePath = Storage::put('/images/exercise', $image);
        if ($thumb_image) $thumb_imagePath = Storage::put('/images/exercise', $thumb_image);
        else $thumb_imagePath = "";
        if ($id == 0) {
            dd($type);
            $exercise = new Exercise();
            self::setDataToExercise($exercise, $name, $imagePath, $thumb_imagePath, $description, $video, $type, $reps, $time_per_rep, $tts_guide, $met);
            $exercise->save();
        } else {
            $exercise = Exercise::find($id);
            if (!$image) $imagePath = $exercise->image;
            self::setDataToExercise($exercise, $name, $imagePath, $thumb_imagePath, $description, $video, $type, $reps, $time_per_rep, $tts_guide, $met);
            $exercise->save();
        }
    }

    public static function setDataToExercise($exercise, $name, $imagePath, $thumb_imagePath, $description, $video, $type, $reps, $time_per_rep, $tts_guide, $met)
    {
        $exercise->name = $name;
        $exercise->image = $imagePath;
        $exercise->thumb_image = $thumb_imagePath;
        $exercise->description = $description;
        $exercise->video = $video;
        $exercise->type = $type;
        $exercise->reps = $reps;
        $exercise->time_per_rep = $time_per_rep;
        $exercise->tts_guide = $tts_guide;
        $exercise->met = $met;
    }


}
