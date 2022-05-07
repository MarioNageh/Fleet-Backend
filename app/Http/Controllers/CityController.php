<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Util\BaseMessage;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getAllCities()
    {
        return (new BaseMessage("Successful Loaded Data", "تم تحميل البيانات بنجاح", 200,City::all()))->toJson();
    }
}
