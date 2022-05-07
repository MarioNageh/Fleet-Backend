<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $table = 'trip';
    protected $primaryKey = 'IdTrip';
    public $incrementing = true;

    const CREATED_AT = 'CreationTime';
    const UPDATED_AT = 'UpdateTime';
    public $timestamps = true;

    protected $fillable = [
        'TripName', 'IdBus',
    ];


    protected $hidden = [
        'CreationTime', 'UpdateTime',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'IdBus');
    }

    public function reservation()
    {

        return $this->hasMany(Reservation::class, 'IdTrip');
    }

    public function reservationTrip($workingHour, $date)
    {
        $data = $this->reservation;
        return $data->filter(function ($rev, $key) use ($workingHour, $date) {

            return ($rev->IdWorkingHour == $workingHour && $rev->ReservationDate == $date);
        });
    }

    public function workingHours()
    {

        return $this->hasMany(WorkingHour::class, 'IdTrip');
    }
}
