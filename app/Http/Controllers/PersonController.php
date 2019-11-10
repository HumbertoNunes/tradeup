<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function refunds(Person $person)
    {
        $report = $person->monthlyReport(request('month'), request('year'));

        if (!$report->refunds) {
            return response()->json([], 204);
        }

        return response()->json($report->only('month', 'year', 'totalRefunds', 'refunds'), 200);
    }
}
