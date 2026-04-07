<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialResource;
use App\Models\Social;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
          "data"=>SocialResource::collection(Social::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "title"=>'required|string',
            "link"=>'required|url'
        ]);
        $social=new Social();
        $social->title=$request->title;
        $social->link=$request->link;
        $social->save();
        return response()->json([
           "data"=>new SocialResource($social)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        return response()->json([
            "data"=>new SocialResource(Social::findOrFail($id))
         ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            "title"=>'nullable|string',
            "link"=>'nullable|url'
        ]);
        $social=Social::findOrFail($id);
        $social->title=$request->title;
        $social->link=$request->link;
        $social->save();
        return response()->json([
           "data"=>new SocialResource($social)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        Social::findOrFail($id)->delete();
        return sendResponse(200,"Deleted successfully");
    }
}
