<?php

namespace Tagydes;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    public $timestamps = false;

    public function resellers()
    {
    	return $this->belongsTo('Tagydes\Reseller', 'country');
    }
}
