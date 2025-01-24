<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\BlogController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\HomePageController;

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

Route::group(['middleware'=>'api_key'], function () {

    Route::get('/privacypolicy', [PolicyController::class, 'getPrivacyPolicy']);
    Route::get('/warrantypolicy', [PolicyController::class, 'getWarrantyPolicy']);
    Route::get('/terms', [PolicyController::class, 'getTerms']);
    Route::get('/shippingreturns', [PolicyController::class, 'getShippings']);

    Route::get('/getfaqs', [FAQController::class, 'getFaq']);

    Route::get('/getcategory', [InventoryController::class, 'getCategory']);
    Route::get('/getproductlist', [InventoryController::class, 'getProducts']);
    Route::get('/getproduct/{id}', [InventoryController::class, 'getProductDetail']);

    Route::get('/getaboutus', [AboutUsController::class, 'getAboutUs']);
    Route::get('/gethomepage', [HomePageController::class, 'getHomePageData']);

    Route::get('/getBlogs', [BlogController::class, 'getBlog']);
 });
