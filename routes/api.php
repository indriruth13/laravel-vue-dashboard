<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\LibraryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/library', function () {
  $path = Storage::disk('local')->get('library.json');
  $content = json_decode($json, true);
});

Route::get('/sidebar', [SidebarController::class, 'show']);
Route::get('/library', [LibraryController::class, 'show']);
