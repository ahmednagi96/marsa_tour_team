<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Offer;
use App\Models\Trip;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WidgetController extends Controller
{

    public function getData($model, $interval)
    {
        $now = Carbon::now();

        switch ($interval) {
            case 'daily':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'weekly':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'monthly':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'yearly':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            default:
                return response()->json(['error' => 'Invalid interval specified.'], 400);
        }


        // $records = $model::withCount('tours')->whereBetween('created_at', [$start, $end])->get();
        if($model==="Trip"){
        $records = $model::withCount('tours')->whereBetween('created_at', [$start, $end])->get();
        $map_price = $records->map(function($record) {
            return $record->tours->sum('price');
        });
        $costs = $map_price->sum();
        }else{
            $records =  $model::whereBetween('created_at', [$start, $end])->get();
        }



        return [
            'start' => $start->toDateTimeString(),
            'end' => $end->toDateTimeString(),
            "count_records" => $records->count(),
            'total_cost' =>$model==="Trip"?$costs :Tour::sum('price'),
            'records' => $records,
        ];
    }

    public function widget(Request $request)
    {
        $interval = $request->input('interval', 'daily');

        // Get trip data
        $tripData = $this->getData(Trip::class, $interval);

        // Get tour data (if you have a Tour model and want to do a similar query)
        $tourData = $this->getData(Tour::class, $interval);

        return response()->json([
            'trips' => $tripData,
            'tours' => $tourData,
        ]);
    }

    public function counts(Request $request)
    {
        // dd($request->all());
        $date = $request->input('date', 'daily');
        $now = Carbon::now();

    $trip_daily = Trip::whereBetween('created_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])->get()->count();
    $trip_weekly = Trip::whereBetween('created_at', [ $now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->get()->count();
    $trip_monthly = Trip::whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->get()->count();

    $tour_daily = Tour::whereBetween('created_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])->get()->count();
    $tour_weekly = Tour::whereBetween('created_at', [ $now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->get()->count();
    $Tour_monthly = Tour::whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->get()->count();

    $offer_daily = Offer::whereBetween('created_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])->get()->count();
    $offer_weekly = Offer::whereBetween('created_at', [ $now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->get()->count();
    $offer_monthly = Offer::whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->get()->count();


    return response()->json([
   "Trip"=>
       [
        "trip_daily"=>$trip_daily,
        "trip_weekly"=>$trip_weekly,
        "trip_monthly"=>$trip_monthly,
       ] ,

    "Tour"=>
    [
        "tour_daily"=>$tour_daily,
        "tour_weekly"=>$tour_weekly,
        "Tour_monthly"=>$Tour_monthly,
    ],

    "Offer"=>
    [
        "CountOffer"=>$offer_daily,
        "offer_weekly"=>$offer_weekly,
        "offer_monthly"=>$offer_monthly,
    ]


    ]);

     }



    }
