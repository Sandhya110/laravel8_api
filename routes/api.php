<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Stmt\Function_;
use App\Http\Controllers\Api\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/user', function(){
//     return "Hello World";

// });
// Route::post('/user' , function(){
//     return response()->json("Post api hit successfully");

// });
// Route::delete('/user/{id}', function($id){
//     return response("Delete" .$id, 200);
// });

// Route::put('/user/{id}', function($id){
//     return response("put" .$id, 200);

// });

// Route::get('/test', function(){
//     p("working");

// });

Route::post('/user/store', [UserController::class, 'store']);
Route::get('/user/index', [UserController::class, 'index']);
Route::get('/user/get/{flag}', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::delete('/user/delete/{id}', [UserController::class, 'destroy']);
// put method used for update all data
Route::put('/user/update/{id}', [UserController::class, 'update']);
//patch method is used for a single data
Route::patch('user/change-password/{id}', [UserController::class, 'changepassword']);




