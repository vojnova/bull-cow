<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $name
 * @property $number
 * @property $tries
 * @property $time
 * @property $date
 */
class History extends Model
{
    protected $table = 'history';
    public $timestamps = false;
    protected $fillable = ['name', 'number', 'tries', 'time', 'date'];
}
