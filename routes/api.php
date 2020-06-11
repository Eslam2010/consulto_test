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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('products', 'Api\ProductController');
Route::resource('categories', 'Api\CategoryController');
Route::delete('deleteAllExpiredProducts', 'Api\ProductController@deleteAllExpiredProduct');
Route::get('mostFiveCategories', 'Api\CategoryController@mostFiveCategories');
Route::get('highFivePriseProducts/{id}', 'Api\ProductController@highFivePriseProducts');
//addImages

Route::post('addImagesToProduct', 'Api\ProductController@addImages');
Route::post('updateImagesOfProduct/{id}', 'Api\ProductController@updateImagesOfProduct');




