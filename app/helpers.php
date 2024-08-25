<?php

if (!function_exists('sendResponse')) {
    function sendResponse($statuse, $msg = null, $data = [])
    {
        $response = [
            'status' => $statuse,
            'msg' => $msg,
            'data' => $data,
        ];
        return response()->json($response, $statuse);
    }
}
if (!function_exists('uploadPhoto')) {
    function uploadPhoto($request_name, $local_directory, $fileSystem_disk, \Illuminate\Http\Request $request)
    {
        $photo_path = null;
        if ($request->hasFile($request_name)) {
            $photo = $request->file($request_name)->getClientOriginalName();
            $photo_path = $request->file($request_name)->store($local_directory, $fileSystem_disk);
        }
        return $photo_path;
    }
}
if (!function_exists('updatePhoto')) {
    function updatePhoto($request_name, $local_directory, $fileSystem_disk, \Illuminate\Http\Request $request)
    {
        $photo_path = null;
        if ($request->hasFile($request_name)) {
            $photo = $request->file($request_name)->getClientOriginalName();
            $photo_path = $request->file($request_name)->store($local_directory, $fileSystem_disk);
        }
        return $photo_path;
    }
}
const PAGINATION = 5;

const BASEURLPHOTO = 'https://alrmoz.com/marsa_tour/public/images/';
