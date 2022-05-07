<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Trip;
use App\Util\BaseMessage;
use Illuminate\Http\Request;

class BusController extends Controller
{
    //
    public function getAllBuses()
    {
        return (new BaseMessage("Successful Loaded Data", "تم تحميل البيانات بنجاح", 200, Bus::all()))->toJson();
    }
}
