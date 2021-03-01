<?php

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

//Route::middleware('auth:sanctum')->group(function () {
    Route::get('application/structure', [\App\Http\Controllers\API\Application\StructureController::class, 'routes']);

    Route::group(['prefix' => 'scm'], function() {
        Route::apiResource('items', \App\Http\Controllers\API\SCM\ItemController::class);
        Route::apiResource('items.item_variants', \App\Http\Controllers\API\SCM\ItemVariantController::class);
        Route::apiResource('storages', \App\Http\Controllers\API\SCM\StorageController::class);
        Route::apiResource('outlets', \App\Http\Controllers\API\SCM\OutletController::class);
        Route::apiResource('vendors', \App\Http\Controllers\API\SCM\VendorController::class);
        Route::apiResource('purchase_headers', \App\Http\Controllers\API\SCM\PurchaseHeaderController::class);
        Route::apiResource('purchase_headers.purchase_lines', \App\Http\Controllers\API\SCM\PurchaseLineController::class);
        Route::apiResource('sales_headers', \App\Http\Controllers\API\SCM\SalesHeaderController::class);
        Route::apiResource('sales_headers.sales_lines', \App\Http\Controllers\API\SCM\SalesLineController::class);
    });

    Route::group(['prefix' => 'administration'], function() {
        Route::apiResource('employees', \App\Http\Controllers\API\Administration\EmployeeController::class);
    });
//});
