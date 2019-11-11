<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Refund;
use App\Person;

class RefundReportController extends Controller
{
    public function monthly(Request $request, Person $person)
    {
        $report = Refund::report($person, $request->year, $request->month);

        return $report->only('month', 'year', 'totalRefunds', 'refunds');
    }

    public function export(Request $request, Person $person)
    {
        $refunds = Refund::whereYear('date', $request->year)->whereMonth('date', $request->month)->get();
        
        abort_if($refunds->isEmpty(), 204);

        return Refund::report($person, $request->year, $request->month)->export();
    }
}
