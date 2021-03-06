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
    public static function createRefunds($request)
    {
        $person = Person::firstOrNew(
            ['identification' => $request->identification],
            [
                'name' => $request->name,
                'identification' => $request->identification,
                'jobRole' => $request->jobRole,
                'createdAt' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

        $person->save();

        $refund = $person->refunds()->create([
            'date' => Carbon::create($request->refunds[0]['date'])->format('Y-m-d H:i:s'),
            'type' => $request->refunds[0]['type'],
            'description' => $request->refunds[0]['description'],
            'value' => $request->refunds[0]['value']
        ]);

        if ($request->has('image')) {
            $refund->image = $refund->imageUpload($request);
        }

        $refund->setDateFormat(\DateTime::ATOM);

        return $refund;
    }
}
