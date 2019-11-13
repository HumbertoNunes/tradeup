<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Person extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * Finds or creates a user and associates the new refund.
     *
     * @param  int  $attributes
     * @return \App\Person
     */
    public static function createRefunds($attributes)
    {
        $person = Person::firstOrNew(
            ['identification' => $attributes->identification],
            [
                'name' => $attributes->name,
                'identification' => $attributes->identification,
                'jobRole' => $attributes->jobRole,
                'createdAt' => Carbon::create($attributes->createdAt)
            ]
        );

        $person->save();

        return $person->refunds()->create([
            'date' => Carbon::create($attributes->refunds[0]['date']),
            'type' => $attributes->refunds[0]['type'],
            'description' => $attributes->refunds[0]['description'],
            'value' => $attributes->refunds[0]['value']
        ]);
    }
}
