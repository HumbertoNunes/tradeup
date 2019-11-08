<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function person()
    {
    	return $this->belongsTo(Person::class);
    }
}
