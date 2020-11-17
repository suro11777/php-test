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
Route::post('login', 'Api\AuthController@login')->name('login');
Route::post('register', 'Api\AuthController@register')->name('register');
Route::get('categories', 'Api\CategoryController@getAllCategories')->name('get-categories');
Route::get('products-by-category/{category_id}', 'Api\ProductController@productsByCategoryId')->name('get-products.by.category_id');
Route::group([
    'middleware' => [\App\Http\Middleware\ApiAuth::class],
    'namespace' => 'Api'
] , function () {
    Route::post('products', 'ProductController@store')->name('product.store');
    Route::post('products/{product}', 'ProductController@update')->name('product.update');
    Route::delete('products/{id}', 'ProductController@delete')->name('product.delete');
    Route::post('categories', 'CategoryController@store')->name('category.store');
    Route::post('categories/{id}', 'CategoryController@update')->name('category.update');
    Route::delete('categories/{id}', 'CategoryController@delete')->name('category.delete');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
