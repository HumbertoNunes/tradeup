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

    public static function createRefunds($attributes)
    {
    	$person = Person::firstOrNew(
            ['identification' => $attributes->identification],
            [
                'name' => $attributes->name,
                'identification' => $attributes->identification,
                'jobRole' => $attributes->jobRole,
                'createdAt' => $attributes->createdAt
            ]
        );

        $person->save();

        $person->refunds()->create([
            'date' => $attributes->refunds[0]['date'],
            'type' => $attributes->refunds[0]['type'],
            'description' => $attributes->refunds[0]['description'],
            'value' => $attributes->refunds[0]['value']
        ]);

        $person->refunds;

        return $person;
    }
}
