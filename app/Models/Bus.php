<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;
    protected $table = 'bus';
    protected $primaryKey = 'IdBus';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'DriverName', 'VehicleRegistrationPlate','AvailableSeats'
    ];



}
