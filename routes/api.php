<?php

use Illuminate\Http\Request;
use App\Product;
use App\Category;

//Route::middleware(['auth:api'])->group(function () {

    Route::prefix('products')->group(function () {

        Route::get('/', 'Api\ProductController@index');
        Route::post('/', 'Api\ProductController@store');
        Route::get('/{products}', 'Api\ProductController@show');
        Route::put('/{products}', 'Api\ProductController@update');
        Route::delete('/{products}', 'Api\ProductController@delete');

    });

    Route::prefix('categories')->group(function () {

        Route::get('/', 'Api\CategoryController@index');
        Route::post('/', 'Api\CategoryController@store');
        Route::get('/{categories}', 'Api\CategoryController@show');
        Route::put('/{categories}', 'Api\CategoryController@update');
        Route::delete('/{categories}', 'Api\CategoryController@delete');

    });

    Route::prefix('tags')->group(function () {

        Route::get('/', 'Api\TagController@index');
        Route::post('/', 'Api\TagController@store');
        Route::get('/{tags}', 'Api\TagController@show');
        Route::put('/{tags}', 'Api\TagController@update');
        Route::delete('/{tags}', 'Api\TagController@destroy');

    });

    Route::prefix('prices')->group(function () {

        Route::get('/', 'Api\PriceController@index');
        Route::post('/', 'Api\PriceController@store');
        Route::get('/{prices}', 'Api\PriceController@show');
        Route::put('/{prices}', 'Api\PriceController@update');
        Route::delete('/{prices}', 'Api\PriceController@delete');

    });

    Route::prefix('personal_price')->group(function () {

        Route::get('/', 'Api\PersonalPriceController@index');
        Route::post('/', 'Api\PersonalPriceController@store');
        Route::get('/{personal_price}', 'Api\PersonalPriceController@show');
        Route::put('/{personal_price}', 'Api\PersonalPriceController@update')->name('personal_price.update');
        Route::delete('/{personal_price}', 'Api\PersonalPriceController@delete');

    });

    Route::prefix('price_type')->group(function () {

        Route::get('/', 'Api\PriceTypeController@index');
        Route::post('/', 'Api\PriceTypeController@store');
        Route::get('/{price_type}', 'Api\PriceTypeController@show');
        Route::put('/{price_type}', 'Api\PriceTypeController@update');
        Route::delete('/{price_type}', 'Api\PriceTypeController@destroy');

    });

    Route::prefix('stores')->group(function () {

        Route::get('/', 'Api\StoreController@index');
        Route::post('/', 'Api\StoreController@store');
        Route::get('/{stores}', 'Api\StoreController@show');
        Route::put('/{stores}', 'Api\StoreController@update');
        Route::delete('/{stores}', 'Api\StoreController@destroy');

    });

    Route::prefix('users')->group(function () {

        Route::get('/', 'Api\UserController@index');
        Route::post('/', 'Api\UserController@store');
        Route::get('/{users}', 'Api\UserController@show');
        Route::put('/{users}', 'Api\UserController@update');
        Route::delete('/{users}', 'Api\UserController@destroy');

    });

    Route::prefix('orders')->group(function () {

        Route::get('/', 'Api\OrderController@index');
        Route::post('/', 'Api\OrderController@store');
        Route::get('/{orders}', 'Api\OrderController@show');
        Route::put('/{orders}', 'Api\OrderController@update');
        Route::delete('/orders', 'Api\OrderController@delete');

    });

    Route::prefix('currencies')->group(function () {
        Route::put('/{currency}', 'Api\CurrencyController@update');
    });
//});
