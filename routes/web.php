<?php

use App\Http\Controllers\DeliveryHistoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DemandNoticeTrackerController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true, 'register' => false]); //, 'register' => false

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('example');
    });
    Route::get('/home', function () {
        return view('example');
    });
    Route::prefix('deliveries')->group(function () {
        Route::get('/', [DeliveryController:: class, 'index']);
        Route::get('gather_project/{id}',[DeliveryController::class, 'gather_project']);
        Route::get('gather_beneficiaries/{id}/{type}',[DeliveryController::class, 'gather_beneficiaries']);
        Route::post('store', [DeliveryController::class, 'store']);
    });
    Route::prefix('demandnotice')->group(function () {
        Route::get('/', [DemandNoticeTrackerController::class, 'index']);
        Route::get('/show', [DemandNoticeTrackerController::class, 'show']);
        Route::post('/store', [DemandNoticeTrackerController::class, 'store']);
        Route::put('/update/{id}', [DemandNoticeTrackerController::class, 'update']);
        Route::get('/destroy/{id}', [DemandNoticeTrackerController::class, 'destroy']);
        Route::get('/show', [DemandNoticeTrackerController::class, 'show']);

    });
    Route::prefix('notice-viewer')->group(function () {
        Route::get('/', [DeliveryHistoryController::class, 'index']);
        Route::get('/gather_projects/{id}',[DeliveryHistoryController::class, 'gather_projects']);
        Route::post('/gather_data', [DeliveryHistoryController::class, 'gather_data']);
    });
});

// Route::get('/clear-photos', function(){
//     $folderPath = public_path('uploads/deliveries');
//     $files = glob($folderPath.'/*'); // get all file names
//     foreach($files as $file){ // iterate files
//         if(is_file($file))
//             unlink($file); // delete file
//     }
//     return 'Photos cleared!';
// });

