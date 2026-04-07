<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Tour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use Illuminate\Support\Facades\File;

class TourController extends Controller
{

    public function is_favourite()
    {
       return response()->json([
           "data" =>new TourResource(Tour::where('is_favourite',1)->get())
       ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "data"=>TourResource::collection(Tour::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=$request->validate([
        'trip_id'=>"nullable|exists:trips,id",
        'duration'=>"nullable|string",
        'price'=>"nullable|numeric",
        'photos.*.'=>"nullable|image",
        'video'=>"nullable|mimes:mp4,avi,mpeg,qt|max:20480",
        'name'=>"required|string",
        'description'=>"nullable|string",
        'services.*'=>"nullable|string",
        'addintional_data.*.'=>"nullable|string",
        'country'=>"nullable|string",
        'city'=>"nullable|string",
        'street'=>"nullable|string",
        ]);
     $tour= Tour::create([
        'trip_id'=>$data['trip_id'],
        'duration'=>$data['duration'],
        'price'=>$data['price'],
         ]);

            $photoPaths = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPaths[] =$photo->store('tours','image');
                }
            }
        $tour->photos=json_encode($photoPaths);
        $tour->name=$data['name'];
        $tour->description=$data['description'];
        $tour->services=json_encode($request->input('services'));
        $tour->country=$data['country'];
        $tour->city=$data['city'];
        $tour->street=$data['street'];
        $tour->additional_data =json_encode($request->input('additional_data'));
        $tour->save();

        return response()->json([
                "data"=>new TourResource($tour)
        ]);


    }

    public function show(Tour $tour)
    {
        return response()->json([
            "data"=>$tour
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data=$request->validate([
            'trip_id'=>"nullable|exists:trips,id",
            'duration'=>"nullable|string",
            'price'=>"nullable|numeric",
            'photos.*'=>"nullable|image",
            'name'=>"nullable|string",
            'description'=>"nullable|string",
            'services.*'=>"nullable|string",
            'addintional_data.*.'=>"nullable|string",
            'country'=>"nullable|string",
            'city'=>"nullable|string",
            'street'=>"nullable|string",
            ]);
            $tour=Tour::findOrFail($id);
            $newData = $request->input('additional_data', []);
            $tour->additional_data = json_encode($newData);
            $newServices = $request->input('services', []);
            $tour->services = json_encode($newServices);
            $tour->duration=$data['duration'];
            $tour->price=$data['price'];
            // $tour->photos=json_encode($photoPaths);
            $tour->name=$data['name'];
            $tour->description=$data['description'];
            $tour->country=$data['country'];
            $tour->city=$data['city'];
            $tour->street=$data['street'];

            if ($request->hasFile('photos')) {
                $existingPhotos = json_decode($tour->photos, true);
            if (is_array($existingPhotos)) {
                foreach ($existingPhotos as $image) {
                File::delete($image);
                }
            }
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('tours', 'image');
            }
            $tour->photos = json_encode($photoPaths);
           }
            $tour->save();

            return response()->json([
                "data"=> new TourResource($tour)
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
        $tour->delete();
        return sendResponse(200,"Deleted successfully");
    }
}
