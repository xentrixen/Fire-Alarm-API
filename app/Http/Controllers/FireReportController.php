<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FireReport;
use Storage;
use App\Http\Resources\FireReport as FireReportResource;
use Cloudinary\Uploader;

class FireReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FireReportResource::collection(FireReport::all());
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
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'image' => 'required|string',
        ]);
        
        $image = "data:image/png;base64,".$request->image;
        $result = Uploader::upload($image);

        $fireReport = new FireReport();
        $fireReport->latitude = $request->latitude;
        $fireReport->longitude = $request->longitude;
        $fireReport->image = $result["secure_url"];
        $fireReport->image_id = $result["public_id"];
        $fireReport->citizen_id = $request->user()->id;

        if($fireReport->save()) {
            return response()->json(['message' => 'Fire report created successfully'], 200);
        }
        return response()->json(['message' => 'An error has occurred'], 500);
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
            return new FireReportResource(FireReport::find($id));
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
        $fireReport = FireReport::find($id);

        if($fireReport) {
            $request->validate([
                'level_of_fire' => 'sometimes|required|in:First Alarm,Second Alarm,Third Alarm,General Alarm',
            ]);

            $fireReport->level_of_fire = $request->level_of_fire;

            if($fireReport->save()) {
                return response()->json(['message' => 'Fire report updated successfully'], 200);
            }
            return response()->json(['message' => 'An error has occurred'], 500);
        } else {
            return response()->json(['message' => 'Fire report not found'], 404);
        }
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
