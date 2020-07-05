<?php

namespace App\Http\Controllers;

use App\Http\Resources\Zone\ZoneCollection;
use App\Models\Zone;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    use ApiResponse;

    public function getZoneList()
    {
        $zones = Zone::paginate(2);
        //chi can co {{dât-link}} no tu phan trang luon
        $result = new ZoneCollection($zones);
        return $this->successResponse($result,'success');
    }
}
