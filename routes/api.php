<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatHistoryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//API for Chat

Route::group(['prefix' => 'chats'], function(){
    Route::get('/getHistory',[ChatHistoryController::class,'index']);
    Route::post('/storeHistory',[ChatHistoryController::class,'store']);
    Route::post('/countMessage',[ChatHistoryController::class,'countMessage']);
    Route::post('/showMessges',[ChatHistoryController::class,'showMessges']);
    Route::post('/readMsg',[ChatHistoryController::class,'readMsg']);
    Route::post('/loadAllMsg',[ChatHistoryController::class,'loadAllMsg']);
    Route::post('/deleteMsg',[ChatHistoryController::class,'deleteMsg']);
});