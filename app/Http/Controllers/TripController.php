<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Trip;
use App\Models\TripPath;
use App\Models\WorkingHour;
use App\Util\BaseMessage;
use Illuminate\Http\Request;

class TripController extends Controller
{

    public function getAllTrips()
    {
        return (new BaseMessage("Successful Loaded Data", "تم تحميل البيانات بنجاح", 200, Trip::all()))->toJson();
    }

    public function tripWorkingHour($id)
    {
        $workingHour = WorkingHour::where(['IdTrip' => $id])->orderBy('DayOfWeek')->orderBy('TripStatringTime')->get();
        return (new BaseMessage("Successful Loaded Data", "تم تحميل البيانات بنجاح", 200, $workingHour))->toJson();
    }

    public function getTripPath($id)
    {
        $tripPath = TripPath::where(['IdTrip' => $id])->orderBy('Order')->get();
        $trip_format="";
        $formatter=" -------> ";
        foreach ($tripPath as $path){
            if($path->NextIdTripPath==null)
                $formatter='';
            $trip_format .= "{$path->city->CityNameEn}{$formatter}";
        }
        return (new BaseMessage("Successful Loaded Data", "تم تحميل البيانات بنجاح", 200, ["Format"=>$trip_format,"Data"=>$tripPath]))->toJson();
    }

}
