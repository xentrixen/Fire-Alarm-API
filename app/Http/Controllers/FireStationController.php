<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FireStation;
use Hash;

class FireStationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FireStation::all();
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
            'name' => 'required|string',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'username' => 'required|string|unique:fire_stations',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $fireStation = new FireStation();
        $fireStation->name = $request->name;
        $fireStation->latitude = $request->latitude;
        $fireStation->longitude = $request->longitude;
        $fireStation->username = $request->username;
        $fireStation->password = Hash::make($request->password);

        if($fireStation->save()) {
            return response()->json(['message' => 'Fire station created successfully'], 200);
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
        $fireStation = FireStation::find($id);
        if($fireStation) {
            return FireStation::find($id);
        } else {
            return response()->json(['message' => 'Fire station not found'], 404);
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
        $fireStation = FireStation::find($id);

        if($fireStation) {
            $request->validate([
                'name' => 'sometimes|required|string',
                'latitude' => 'sometimes|required|numeric|min:-90|max:90',
                'longitude' => 'sometimes|required|numeric|min:-180|max:180',
                'username' => 'sometimes|required|string',
                'password' => 'sometimes|required|string|min:8|confirmed'
            ]);

            $inputs = $request->only(['name', 'username', 'latitude', 'longitude', 'password']);
            foreach($inputs as $key => $value) {
                if($key == 'password') {
                    $fireStation->password = Hash::make($value);
                } else {
                    $fireStation->$key = $value;
                }
            }

            if($fireStation->save()) {
                return response()->json(['message' => 'Fire station updated successfully'], 200);
            }
            return response()->json(['message' => 'An error has occurred'], 500);
        } else {
            return response()->json(['message' => 'Fire station not found'], 404);
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
        $fireStation = FireStation::find($id);
        if($fireStation) {
            if($fireStation->delete()) {
                return response()->json(['message' => 'Fire station deleted successfully'], 200);
            }
            return response()->json(['message' => 'An error has occurred'], 500);
        } else {
            return response()->json(['message' => 'Fire station not found'], 404);
        }
    }
}
