<?php


namespace App\Http\Service;


use App\Models\Exercise;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\This;
use function Symfony\Component\VarDumper\Dumper\esc;

class ExerciseService
{
    use ApiResponse;

    public static function saveToExerciseTable($id=0,$name,$image,$thumb_image,$description,$video,$duration,$tts_guide,$met){
        //Store and get path
        $imagePath = Storage::put('/images/exercise',$image);
        $thumb_imagePath = Storage::put('/images/exercise',$thumb_image);

        if($id==0){
                $exercise = new Exercise();
                self::setDataToExercise($exercise,$name,$imagePath,$thumb_imagePath,$description,$video,$duration,$tts_guide,$met);
                $exercise->save();
        } else {
            $exercise = Exercise::find($id);
            self::setDataToExercise($exercise,$name,$imagePath,$thumb_imagePath,$description,$video,$duration,$tts_guide,$met);
            $exercise->save();
        }
    }

    public static function setDataToExercise($exercise,$name,$imagePath,$thumb_imagePath,$description,$video,$duration,$tts_guide,$met){
        $exercise->name = $name;
        $exercise->image = $imagePath;
        $exercise->thumb_image = $thumb_imagePath;
        $exercise->description=$description;
        $exercise->video=$video;
        $exercise->duration=$duration;
        $exercise->tts_guide=$tts_guide;
        $exercise->met=$met;
    }


}
