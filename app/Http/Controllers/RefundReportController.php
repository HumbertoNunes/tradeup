<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Refund;
use App\Person;

class RefundReportController extends Controller
{
    public function monthly(Request $request, Person $person)
    {
        abort_if(Refund::filter($request, $person)->isEmpty(), 204);

        $report = Refund::report($request, $person);

        return response()->json($report, 200);
    }

    public function export(Request $request, Person $person)
    {
        $refunds = Refund::filter($request, $person);
        
        abort_if($refunds->isEmpty(), 204);

        $fileName = Refund::report($request, $person)->export();

        return response()->download($fileName)->deleteFileAfterSend();
    }
}
