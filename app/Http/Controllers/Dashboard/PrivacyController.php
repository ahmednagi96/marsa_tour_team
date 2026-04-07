<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Privacy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PolicyResource;

class PrivacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "data"=> PolicyResource::collection(Privacy::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text'=>"string|nullable",
            "file"=>"nullable|file|mimes:pdf,doc,docx",
        ]);
        $privacy = New Privacy;
        $privacy->text = $request->input('text');
        if($request->hasFile('file')){
            $privacy->file =updatePhoto('file','privacys','image',$request);
        }
        $privacy->save();
        return response()->json([
            "data" => new PolicyResource($privacy)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
     $privacy = Privacy::findOrFail($id);
        return new PolicyResource($privacy);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            "file"=>"nullable|file|mimes:pdf,doc,docx",
            'des.*'=>"string|nullable"
        ]);
        $privacy =Privacy::findOrFail($id);
        $photo=$request->file('file');
        if($request->hasFile('file')){
            $privacy->file =updatePhoto('file','privacys','image',$request);

        }
        $privacy->des = json_encode($request->input('des'));
        $privacy->save();
        return response()->json([
            "data" => new PolicyResource($privacy)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $privacy = Privacy::findOrFail($id);
        $privacy->delete();
        return sendResponse(200,"Deleted successfully");
    }
}
