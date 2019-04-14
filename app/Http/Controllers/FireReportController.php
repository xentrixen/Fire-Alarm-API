<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FireReport;

class FireReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FireReport::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'latitude' => 'required|numeric|min:-90|max:90',
                'longitude' => 'required|numeric|min:-180|max:180',
                'image' => 'required',
                'citizen_id' => 'required|exists:citizens,id'
            ]);
    
            $fireReport = new FireReport();
            $fireReport->latitude = $request->latitude;
            $fireReport->longitude = $request->longitude;
            $fireReport->image = $request->image;
            $fireReport->citizen_id = $request->citizen_id;
    
            if($fireReport->save()) {
                return response()->json(['message' => 'Fire report created successfully'], 200);
            }
            return response()->json(['message' => 'An error has occurred'], 500);
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fireReport = FireReport::find($id);
        if($fireReport) {
            return FireReport::find($id);
        } else {
            return response()->json(['message' => 'Fire report not found'], 404);
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
        $fireReport = FireReport::find($id);
        if($fireReport) {
            if($fireReport->delete()) {
                return response()->json(['message' => 'Fire report deleted successfully'], 200);
            }
            return response()->json(['message' => 'An error has occurred'], 500);
        } else {
            return response()->json(['message' => 'Fire report not found'], 404);
        }
    }
}
