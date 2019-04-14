<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FireHydrant;

class FireHydrantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FireHydrant::all();
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
        ]);

        $fireHydrant = new FireHydrant();
        $fireHydrant->name = $request->name;
        $fireHydrant->latitude = $request->latitude;
        $fireHydrant->longitude = $request->longitude;

        if($fireHydrant->save()) {
            return response()->json(['message' => 'Fire hydrant created successfully'], 200);
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
        $fireHydrant = FireHydrant::find($id);
        if($fireHydrant) {
            return FireHydrant::find($id);
        } else {
            return response()->json(['message' => 'Fire hydrant not found'], 404);
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
        $fireHydrant = FireHydrant::find($id);

        if($fireHydrant) {
            $request->validate([
                'name' => 'sometimes|required|string',
                'latitude' => 'sometimes|required|numeric|min:-90|max:90',
                'longitude' => 'sometimes|required|numeric|min:-180|max:180',
            ]);

            $inputs = $request->only(['name', 'latitude', 'longitude']);
            foreach($inputs as $key => $value) {
                $fireHydrant->$key = $value;
            }

            if($fireHydrant->save()) {
                return response()->json(['message' => 'Fire hydrant updated successfully'], 200);
            }
            return response()->json(['message' => 'An error has occurred'], 500);
        } else {
            return response()->json(['message' => 'Fire hydrant not found'], 404);
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
        $fireHydrant = FireHydrant::find($id);
        if($fireHydrant) {
            if($fireHydrant->delete()) {
                return response()->json(['message' => 'Fire hydrant deleted successfully'], 200);
            }
            return response()->json(['message' => 'An error has occurred'], 500);
        } else {
            return response()->json(['message' => 'Fire hydrant not found'], 404);
        }
    }
}
