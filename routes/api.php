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
});
