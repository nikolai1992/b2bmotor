<?php

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


Auth::routes();

Route::any('/1c_exchange', 'ExchangeController@index')
    ->name('1c_exchange');

Route::any('/1c_exchange/test', 'ExchangeController@test')
    ->name('1c_exchange_test');

Route::get('/refresh_tax_selector', 'DashboardController@refreshTaxSellector');

Route::get('/test', function () {
    $records = App\Category::all();
    foreach ($records as $record) {
//        dump($record->title);
//        $record->delete();
    }
    dump('finished');
});

Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/', 'DashboardController@index')
        ->name('dashboard');

    Route::get('/change_currency/{id}', 'CurrencyController@changeCurrency')
        ->name('change_currency');

    Route::prefix('profile')->group(function () {

        Route::get('/', 'ProfileController@index')
            ->name('profile');

        Route::post('/edit', 'ProfileController@update')
            ->name('update_profile');

    });

    Route::prefix('managers')->group(function () {

        Route::get('/', 'ManagerController@index')
            ->name('list_managers')
            ->middleware('can:see-managers-tab');

        Route::get('/create', 'ManagerController@create')
            ->name('create_manager')
            ->middleware('can:create-manager');

        Route::post('/create', 'ManagerController@store')
            ->name('store_manager')
            ->middleware('can:create-manager');

        Route::get('/edit/{manager}', 'ManagerController@edit')
            ->name('edit_manager')
            ->middleware('can:update-manager,manager');

        Route::post('/edit/{manager}', 'ManagerController@update')
            ->name('update_manager')
            ->middleware('can:update-manager,manager');

        Route::get('/{manager}/clients', 'ManagerController@clientsList')
            ->name('manager_list_clients');
        //->middleware('can:update-manager,manager');

        Route::post('/manager/add-clients', 'ManagerController@addClients')
            ->name('manager_add_clients');
        //->middleware('can:update-manager,manager');

    });

    Route::prefix('clients')->group(function () {

        Route::get('/', 'ClientController@index')
            ->name('list_clients')
            ->middleware('can:see-clients-tab');

        Route::get('/edit/{client}', 'ClientController@edit')
            ->name('edit_client')
            ->middleware('can:update-client,client');

        Route::post('/edit/{client}', 'ClientController@update')
            ->name('update_client')
            ->middleware('can:update-client,client');

        Route::any('/update-categories', 'ClientController@updateCategories')
            ->name('update_categories');

    });

    Route::prefix('catalog')->group(function () {

        Route::get('/', 'CatalogController@index')
            ->name('catalog');

        Route::get('/change-prices-tax-status', 'CatalogController@changePricesTaxStatus')
            ->name('change_prices_tax_status');

        Route::get('/search', 'CatalogController@searchByFields')
            ->name('catalog_search_by_fields');

        Route::post('/update', 'CatalogController@update')
            ->name('catalog_update');

        Route::get('/{path?}', 'CatalogController@show')
            ->where('path', '[a-zA-Z0-9/_-]+')
            ->name('catalog_inner');

    });

    Route::prefix('product')->group(function () {
        Route::get('/update_total_amount_and_price', 'ProductController@updateTotalAmountAndPrice');

        Route::get('/{slug}', 'ProductController@show')
            ->name('show_product');

    });

    Route::prefix('orders')->group(function () {

        Route::get('/', 'OrderController@index')
            ->name('list_orders');

        Route::get('/show/{order}', 'OrderController@show')
            ->name('show_order');

        Route::post('/create', 'OrderController@store')
            ->name('store_order');
//            ->middleware('can:create-order');

        Route::get('/edit/{order}', 'OrderController@edit')
            ->name('edit_order');
//            ->middleware('can:update-order,order');

        Route::post('/edit/{order}', 'OrderController@update')
            ->name('update_order')
            ->middleware('can:update-order,order');

        Route::get('/remove/{order}/{product}', 'OrderController@remove')
            ->name('remove_product_from_order');
        //->middleware('can:update-order,order');

        Route::post('/cansele/{order}', 'OrderController@cansele')
            ->name('cansele_order');

    });

    Route::prefix('cart')->group(function () {

        Route::get('/', 'CartController@show')
            ->name('show_cart');

        Route::post('/add/{product}', 'CartController@add')
            ->name('add_to_cart');

        Route::post('/update', 'CartController@update')
            ->name('update_cart');

        Route::any('/remove/{rowId}', 'CartController@remove')
            ->name('remove_from_cart');

        Route::any('/clear', 'CartController@clear')
            ->name('clear_cart');

    });

    Route::prefix('search')->group(function () {

        Route::any('/', 'SearchController@search')
            ->name('search');

    });
    Route::get('/download/{file}', 'DownloadsController@download');
//    Route::prefix('cart')->group(function () {
//
//        Route::post('/add/{product}', 'CartController@add')
//            ->name('add2cart');
//
//    });
    Route::get('/change_pagination_items_count/{count}', 'PaginationController@changePaginationItemsCount')
        ->name('change_pagination_items_count');
});
