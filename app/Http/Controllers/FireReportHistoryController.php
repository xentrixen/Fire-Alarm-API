<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FireReport;
use App\Http\Resources\FireReport as FireReportResource;

class FireReportHistoryController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FireReportResource::collection(FireReport::onlyTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fireReport = FireReport::onlyTrashed()->get()->find($id);
        if($fireReport) {
            return new FireReportResource($fireReport);
        } else {
            return response()->json(['message' => 'Fire report history not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fireReport = FireReport::onlyTrashed()->get()->find($id);
        if($fireReport) {
            if($fireReport->forcedelete()) {
                return response()->json(['message' => 'Fire report history deleted successfully'], 200);
            }
            return response()->json(['message' => 'An error has occurred'], 500);
        } else {
            return response()->json(['message' => 'Fire report history not found'], 404);
        }
    }
}
