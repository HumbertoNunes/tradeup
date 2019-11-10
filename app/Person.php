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
    public function monthlyReport(?int $month, int $year)
    {
        if(empty($month))
            return $this->yearlyReport($year);
        $refunds = $this->refunds()->whereMonth('date', $month)->whereYear('date', $year)->get();

        if ($refunds->isNotEmpty()) {
            $this->totalRefunds = $refunds->count();
            $this->refunds = $refunds->sum('value');
            $this->month = $month;
            $this->year = $year;

            return $this->only('month', 'year', 'totalRefunds', 'refunds');
        }
    }

    /**
     * Shows a person's yearly refund report.
     *
     * @param  int  $year
     * @return \App\Person
     */
    public function yearlyReport(int $year)
    {
        $refunds = $this->refunds()->whereYear('date', $year)->get();

        if ($refunds->isNotEmpty()) {
            $this->totalRefunds = $refunds->count();
            $this->refunds = $refunds->sum('value');
            $this->year = $year;

            return $this->only('year', 'totalRefunds', 'refunds');
        }
    }
}
