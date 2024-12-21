<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\HouseKeeperController;
use App\Http\Controllers\Company\HouseKeeperOrderController;
use App\Http\Controllers\Company\HouseKeeperHourlyOrderController;
use App\Http\Controllers\Company\HomeController;
use App\Http\Controllers\Company\ServiceController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']

], function() {

    Route::group(['middleware' => ['auth:company','company'],'prefix' => 'company','as'=>'company.'], function () {

        Route::view('/', 'company.index')->name('home');


        Route::controller( HouseKeeperController::class)->group(function () {
            Route::group(['as' => 'housekeepers.', 'prefix' => 'housekeepers'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/fetch/{id}', 'fetch')->name('fetch');
                Route::get('/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');

            });
        });



        Route::controller( HouseKeeperOrderController::class)->group(function () {
            Route::group(['as' => 'housekeepers.orders.', 'prefix' => 'housekeepers/orders/'], function () {
                Route::get('/orders', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/view/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');
                Route::post('status', 'updateStatus')->name('updateStatus');
                Route::get('print/{id}', 'print')->name('print');

            });
        });


    Route::controller( ServiceController::class)->group(function () {
            Route::group(['as' => 'services.', 'prefix' => 'services'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::post('/delete', 'destroy')->name('delete');
                Route::get('/fetch/{id}', 'fetch')->name('fetch');
                Route::post('/update', 'update')->name('update');


            });
        });







        Route::group(['as' => 'profile.', 'prefix' => 'profile'], function () {
            Route::view('/', 'company.profile')->name('index');
            Route::post('/update', [HomeController::class, 'profile'])->name('update');
            Route::post('/password', [HomeController::class, 'password'])->name('password');


        });



        Route::controller( HouseKeeperHourlyOrderController::class)->group(function () {
            Route::group(['as' => 'housekeepers.HourlyOrders.', 'prefix' => 'housekeepers/HourlyOrders'], function () {
                Route::get('/orders', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::get('/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('status', 'updateStatus')->name('updateStatus');
                Route::get('print/{id}', 'print')->name('print');
                Route::get('get-housekeepers/{id}', 'getHousekeepers')->name('get-housekeepers');



            });
        });


    });



});












