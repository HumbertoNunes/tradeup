<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}
