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

Route::get('application/structure', [\App\Http\Controllers\API\Application\StructureController::class, 'routes']);

Route::group(['prefix' => 'scm'], function() {
   Route::apiResource('item', \App\Http\Controllers\API\SCM\ItemController::class);
   Route::apiResource('item.variant', \App\Http\Controllers\API\SCM\ItemVariantController::class);
   Route::apiResource('storage', \App\Http\Controllers\API\SCM\StorageController::class);
   Route::apiResource('outlet', \App\Http\Controllers\API\SCM\OutletController::class);
   Route::apiResource('vendor', \App\Http\Controllers\API\SCM\VendorController::class);
   Route::apiResource('purchaseheader', \App\Http\Controllers\API\SCM\PurchaseHeaderController::class);
   Route::apiResource('purchaseheader.purchaseline', \App\Http\Controllers\API\SCM\PurchaseLineController::class);
   Route::apiResource('salesheader', \App\Http\Controllers\API\SCM\SalesHeaderController::class);
   Route::apiResource('salesheader.salesline', \App\Http\Controllers\API\SCM\SalesLineController::class);
});

Route::group(['prefix' => 'administration'], function() {
    Route::apiResource('employee', \App\Http\Controllers\API\SCM\EmployeeController::class);
});
