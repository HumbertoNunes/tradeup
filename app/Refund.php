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

    public function rectify($value)
    {
        if (!$this->isApproved()) {
            $this->value = $value;

            $this->save();
        }

        return $this->isApproved();
    }

    /**
     * Approve the specified refund.
     *
     * @return \App\Refund
     */
    public function approve()
    {
        $this->approved = true;

        $this->save();

        return $this;
    }

    /**
     * Return the current status of the specified refund.
     *
     * @return boolean
     */
    public function isApproved()
    {
        return $this->approved;
    }
}
