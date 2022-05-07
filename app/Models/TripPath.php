<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPath extends Model
{
    use HasFactory;
    protected $table = 'trippath';
    protected $primaryKey = 'IdTripPath';
    public $incrementing = true;


    public $timestamps = false;

    protected $fillable = [
        'IdCity', 'IdTrip', 'NextIdTripPath', 'Order'
    ];


    public function city()
    {
        return $this->belongsTo(City::class, 'IdCity');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'IdTrip');
    }

    public function nextTrip()
    {
        return $this->where('IdTripPath', $this->NextIdTripPath)->first();
    }

    public function hasEndPointInThisPath($idCityEnd)
    {
        $tripPath = $this;

        while ($tripPath->NextIdTripPath != null) {
            $tripPath= $tripPath->nextTrip();
            if ($tripPath->IdCity == $idCityEnd)
                return true;
        }

        return false;

    }
}
