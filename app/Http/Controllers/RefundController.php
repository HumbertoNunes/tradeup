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
        return response()->json(Refund::paginate(10), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $refund = \App\Person::createRefunds($request);

        return response()->json($refund, 201);
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
        abort_if($refund->rectify($request->value), 403);

        return response()->json($refund, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function destroy(Refund $refund)
    {
        $refund->delete();

        return response()->json(['message' => 'Resource deleted.'], 200);
    }

    /**
     * Approve the specified resource.
     *
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function approve(Refund $refund)
    {
        abort_if($refund->isApproved(), 304);

        $refund->approve();

        return response()->json(['message' => 'Resource approved.'], 200);
    }

    public function upload(Request $request, Refund $refund)
    {
        return $refund->imageUpload($request);

    }
}
