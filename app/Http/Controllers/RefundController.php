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
        abort_unless(Refund::exists(), 204);

        return response()->json(Refund::perPage(10), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'identification' => 'required',
            'jobRole' => 'required',
            'createdAt' => 'required|date',
            'refunds.*.date' => 'required|date',
            'refunds.*.type' => 'required',
            'refunds.*.description' => 'required',
            'refunds.*.value' => 'required|numeric',
        ]);

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
        $request->validate([
            'value' => 'required|numeric'
        ]);
        
        if ($refund->rectify($request->value)) {
            return response()->json([
                'message' => 'Refund already approved cannot be modified.'
            ], 422);
        }

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
        if ($refund->isApproved()) {
            return response()->json([
                'message' => "Refund it's already approved."
            ], 422);
        }

        $refund->approve();

        return response()->json(['message' => 'Resource approved.'], 200);
    }

    /**
     * Upload an image and save it to the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, Refund $refund)
    {
        return $refund->imageUpload($request);
    }
}
