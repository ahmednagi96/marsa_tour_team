<?php

use App\Traits\CacheableService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



class TestTag{
    use CacheableService;

    public function index(){
        return $this->rememberWithTags("test_tags","test",fn()=>[1,2,3]);
    }
}
Route::get("/test-tag",[TestTag::class,"index"]);
