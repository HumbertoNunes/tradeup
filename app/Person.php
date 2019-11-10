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

        $person->refunds()->create([
            'date' => Carbon::create($attributes->refunds[0]['date']),
            'type' => $attributes->refunds[0]['type'],
            'description' => $attributes->refunds[0]['description'],
            'value' => $attributes->refunds[0]['value']
        ]);

        $person->refunds;

        return $person;
    }
    
    /**
     * Shows a person's monthly refund report.
     *
     * @param  int  $month,  int  $year
     * @return \App\Person
     */
    public function monthlyReport(int $month, int $year)
    {
        $refunds = $this->refunds()->whereMonth('date', $month)->whereYear('date', $year)->get();
        
        $this->month = $month;
        $this->year = $year;
        $this->totalRefunds = $refunds->count();
        $this->refunds = $refunds->sum('value');

        return $this;
    }
}
