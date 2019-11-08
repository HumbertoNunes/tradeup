<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
	use SoftDeletes;

    public $timestamps = false;

    protected $guarded = [];

    public function person()
    {
    	return $this->belongsTo(Person::class);
    }
}
