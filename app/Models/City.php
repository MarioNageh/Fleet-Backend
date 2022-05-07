<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'city';
    protected $primaryKey = 'IdCity';
    public $incrementing = true;

    const CREATED_AT = 'CreationTime';
    const UPDATED_AT = 'UpdateTime';
    public $timestamps = true;

    protected $fillable = [
        'CityNameEn', 'CityNameAr',
    ];
    protected $hidden = [
        'CreationTime', 'UpdateTime',
    ];

}
