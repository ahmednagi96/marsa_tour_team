<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "data"=> OfferResource::collection(Offer::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,)
    {
        $data=$request->validate([
        "tour_id"=>"required|exists:tours,id",
        "offer_name"=>"nullable|string",
        "offer_price_value"=>"nullable",
        "offer_price_percent"=>"nullable",
        "special_price_start"=>"nullable",
        "special_price_end"=>"nullable"
        ]);
        $offer=Offer::create($data);
        $tourvalue=$offer->tour->price;
        $offer->offer_price_value =$tourvalue * $offer->offer_price_percent /100;
        $offer->offer_name=$data['offer_name'];
        $offer->save();
        return response()->json([
            "data"=> new OfferResource($offer)
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        return response()->json([
            "data"=>new OfferResource($offer)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data=$request->validate([
            "offer_name"=>"nullable|string",
            "offer_price_value"=>"nullable",
            "offer_price_percent"=>"nullable",
            "special_price_start"=>"nullable",
            "special_price_end"=>"nullable"
            ]);
            $offer=Offer::findOrFail($id);
            $offer->update($data);
            $tourvalue=$offer->tour->price;
            $offer->offer_price_value =$tourvalue * $offer->offer_price_percent /100;
            $offer->offer_name=$request->offer_name;
            $offer->save();
            return response()->json([
                "data"=> new OfferResource($offer)
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();
        return sendResponse(200,"Deleted successfully");
    }
}
