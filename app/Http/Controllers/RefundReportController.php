<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Refund;
use App\Person;

class RefundReportController extends Controller
{
    public function monthly(Request $request, Person $person)
    {
        $refunds = Refund::whereYear('date', $request->year)->whereMonth('date', $request->month)->get();
        
        abort_if($refunds->isEmpty(), 204);

        $report = Refund::report($person, $request->year, $request->month);

        return response()->json($report->only('month', 'year', 'totalRefunds', 'refunds'), 200);
    }

    public function export(Request $request, Person $person)
    {
        $refunds = Refund::whereYear('date', $request->year)->whereMonth('date', $request->month)->get();
        
        abort_if($refunds->isEmpty(), 204);

        $fileName = Refund::report($person, $request->year, $request->month)->export();

        return response()->download($fileName)->deleteFileAfterSend();
    }
}
