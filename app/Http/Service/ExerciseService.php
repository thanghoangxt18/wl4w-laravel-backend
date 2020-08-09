<?php


namespace App\Http\Service;


use App\Models\Exercise;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;

class ExerciseService
{
    use ApiResponse;

    public static function saveToExerciseTable($id = 0, $name, $image=null, $thumb_image=null, $description, $video, $duration, $tts_guide, $met)
    {
        if ($image) $imagePath = Storage::put('/images/exercise', $image);
        if ($thumb_image) $thumb_imagePath = Storage::put('/images/exercise', $thumb_image);
        if ($id == 0) {

            $exercise = new Exercise();
            self::setDataToExercise($exercise, $name, $imagePath, $thumb_imagePath, $description, $video, $duration, $tts_guide, $met);
            $exercise->save();
        } else {
            $exercise = Exercise::find($id);
            if (!$image) $imagePath = $exercise->image;
            if (!$thumb_image) $thumb_imagePath = $exercise->thumb_image;
            self::setDataToExercise($exercise, $name, $imagePath, $thumb_imagePath, $description, $video, $duration, $tts_guide, $met);
            $exercise->save();
        }
    }

    public static function setDataToExercise($exercise, $name, $imagePath, $thumb_imagePath, $description, $video, $duration, $tts_guide, $met)
    {
        $exercise->name = $name;
        $exercise->image = $imagePath;
        $exercise->thumb_image = $thumb_imagePath;
        $exercise->description = $description;
        $exercise->video = $video;
        $exercise->duration = $duration;
        $exercise->tts_guide = $tts_guide;
        $exercise->met = $met;
    }


}
