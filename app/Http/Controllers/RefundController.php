<?php

namespace App\Http\Controllers;

use App\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $person = \App\Person::firstOrNew(
            ['identification' => $request->identification],
            [
                'name' => $request->name,
                'identification' => $request->identification,
                'jobRole' => $request->jobRole,
                'createdAt' => $request->createdAt
            ]
        );

        $person->save();

        $person->refunds()->create([
            'date' => $request->refunds[0]['date'],
            'type' => $request->refunds[0]['type'],
            'description' => $request->refunds[0]['description'],
            'value' => $request->refunds[0]['value']
        ]);

        $person->refunds;

        return response()->json($person, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function show(Refund $refund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Refund $refund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function destroy(Refund $refund)
    {
        //
    }
}
