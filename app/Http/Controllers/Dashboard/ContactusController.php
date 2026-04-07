<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Resources\ContactusResource;
use App\Models\Contactus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "data" =>  ContactusResource::collection(Contactus::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $data=$request->validate([
    "name"=>"required|string",
    "phone"=>"required|min:12|numeric",
    // 'phone' => 'required|phone:AUTO' // AUTO detects the default country
    //composer require propaganistas/laravel-phone

    // "photo"=>"nullable|image"
    ]);
    $data['photo']=uploadPhoto('photo', 'contactus', 'image', $request);
        $contactus=  Contactus::create($data);
        $contactus->name=$request->name;
        $contactus->save();
        return response()->json([
        "data"=>new ContactusResource ($contactus)
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        return response()->json([
            "data"=> new ContactusResource(Contactus::findOrFail($id))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data=$request->validate([
            "name"=>"required|string",
            "email"=>"required|string",
            "photo"=>"nullable|image"
            ]);
            $contactus=Contactus::findOrFail($id);
            $data['photo']=updatePhoto('photo', 'contactus', 'image', $request);
            $contactus->name=$request->name;
                $contactus->update($data);
                return response()->json([
                "data"=>$contactus
                ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $contactus=Contactus::findOrFail($id);
        $contactus->delete();
        return sendResponse(200,"Deleted successfully");
    }
}
