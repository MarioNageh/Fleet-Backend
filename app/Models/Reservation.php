<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table = 'reservation';
    protected $primaryKey = 'IdReservation';
    public $incrementing = true;

    public $mario;
    const CREATED_AT = 'CreationTime';
    const UPDATED_AT = 'UpdateTime';
    public $timestamps = true;

    protected $fillable = [
        'IdBus', 'IdTrip', 'IdPathTripStart', 'IdPathTripEnd', 'BusLocation','IdWorkingHour','ReservationDate','IsArrived','IdUser'
    ];
    protected $hidden = [
        'CreationTime', 'UpdateTime','startPath','endPath','workingHour'
    ];


    protected $appends = ['format_shape'];

    public function getFormatShapeAttribute() {
        return "{$this->startPath->city->CityNameEn} -----> {$this->endPath->city->CityNameEn}";
    }

    public function but()
    {
        return $this->belongsTo(Bus::class, 'IdBus');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'IdTrip');
    }

    public function startPath()
    {
        return $this->belongsTo(TripPath::class, 'IdPathTripStart');
    }

    public function endPath()
    {
        return $this->belongsTo(TripPath::class, 'IdPathTripEnd');
    }
    public function workingHour()
    {
        return $this->belongsTo(WorkingHour::class, 'IdWorkingHour');
    }


}
