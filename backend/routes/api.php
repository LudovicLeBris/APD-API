<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApdController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/ductdimension', [ApdController::class, 'optimalDuctDimension'])->name('get-ductDimension');
Route::post('/ductsection', [ApdController::class, 'ductSection'])->name('get-ductSection');
Route::post('/flowspeed', [ApdController::class, 'flowSpeed'])->name('get-flowSpeed');

Route::get('/diameters', [ApdController::class, 'listDiameters'])->name('diameters-list');
Route::get('/materials', [ApdController::class, 'listMaterials'])->name('materials-list');
Route::get('/singularities/{shape}', [ApdController::class, 'listSingularities'])->name('singularities-list');

Route::post('/section', [ApdController::class, 'setSection'])->name('set-section');
Route::post('/sections', [ApdController::class, 'setSections'])->name('set-sections');