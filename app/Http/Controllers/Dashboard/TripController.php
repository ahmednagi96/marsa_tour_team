<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Trip;
use App\Models\TripTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use Illuminate\Support\Facades\Storage;
class TripController extends Controller
{


    public function search(Request $request)
{
    $query = $request->input('query');
    $trips = TripTranslation::with('tours')
        ->where('name', 'LIKE', '%' . $query . '%')
        ->orWhereHas('tours', function ($q) use ($query) {
        $q->where('name', 'LIKE', '%' . $query . '%');
        })->get();
        return response()->json($trips);
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "data" => TripResource::collection(Trip::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $data=request()->validate([
        "name"=>'required|string',
        'photo'=>"nullable|File::types(['png', 'jpg', 'webp'])",
        'description'=>'nullable|string',
    ]);
    // $photo = $request->file("photo")->store('trips');
    $trip=Trip::create($data);
        $trip->name=$request->name;
        $trip->description=$request->description;
        $trip->photo= uploadPhoto('photo', 'trips', 'image', $request);

        $trip->save();
      return response()->json([
          "data"=>new TripResource($trip)
      ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        return response()->json([
            "data"=>new TripResource($trip)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $data=request()->validate([
            "name"=>'nullable|string',
            'photo'=>"nullable|File::types(['png', 'jpg', 'webp'])",
            'description'=>'nullable|string',
         ]);
         if($request->hasFile('photo')){
            $trip->photo = updatePhoto('photo','trips','image',$request);
         }

        $trip->name = $request->name;
        $trip->description = $request->description;
        $trip->save;

         return response()->json([
          "data"=>new TripResource($trip)
         ]);



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return sendResponse(200,"Deleted successfully");

    }
}
