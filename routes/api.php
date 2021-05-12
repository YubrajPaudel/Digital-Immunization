<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\ChildVaccineController;
use App\Http\Controllers\HealthPersonnelController;
use App\Http\Controllers\VaccineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('vaccines', VaccineController::class)->missing(function () {
    return response()->json(['Vaccine Not Found'], 404);
});
Route::apiResource('children', ChildController::class)->missing(function () {
    return response()->json(['Child Not Found'], 404);
});
//For child vaccination records
Route::prefix('children/{child}')->name('children.vaccines.')->group(function () {
    //View all vaccines that the child is vaccinated with
    Route::get('/vaccines', [ChildVaccineController::class, 'index'])->name('index');
    //Add the vaccine to the vaccination record for the child
    Route::post('/vaccines/{vaccine}', [ChildVaccineController::class, 'store'])->name('store')->missing(function () {
        return response()->json(['Resource Not Found'], 404);
    });
    //Delecte the vaccine from the vaccination record for the child
    Route::delete('/vaccines/{vaccine}', [ChildVaccineController::class, 'destroy'])->name('destroy')->missing(function () {
        return response()->json(['Resource Not Found'], 404);
    });
});
//Health Personnel using  User Model
Route::apiResource('health-personnels', HealthPersonnelController::class)->parameters([
    'health-personnels' => 'user',
])->missing(function () {
    return response()->json(['Health Personnel Not Found'], 404);
});
//Authentication Routes
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
