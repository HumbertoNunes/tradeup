<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;

class RefundReportController extends Controller
{
    public function yearly(Request $request, Person $person, int $year)
    {
        $report = $person->yearlyReport($request->year);

        abort_if(empty($report), 204);

        return response()->json($report, 200);
    }

    public function monthly(Request $request, Person $person, int $year, int$month)
    {
        $report = $person->monthlyReport($request->month, $request->year);

        abort_if(empty($report), 204);

        return response()->json($report, 200);
    }
}
