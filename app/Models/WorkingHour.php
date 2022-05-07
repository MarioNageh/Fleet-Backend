<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    use HasFactory;

    protected $table = 'working_hours';
    protected $primaryKey = 'IdWorkingHour';
    public $incrementing = true;

    const CREATED_AT = 'CreationTime';
    const UPDATED_AT = 'UpdateTime';
    public $timestamps = true;
    //UpdateTime
    protected $hidden = [
        'CreationTime', 'UpdateTime',
    ];

    protected $fillable = [
        'IdTrip', 'DayOfWeek','TripStatringTime','TripEndingTime'
    ];
    public function hasWorkingIsThisDay($dayOfWeekNumber)
    {
        return $this->DayOfWeek == $dayOfWeekNumber;
    }

}
