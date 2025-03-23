<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\HouseKeeperController;
use App\Http\Controllers\Admin\HouseKeeperOrderController;
use App\Http\Controllers\Admin\AssurancesController;
use App\Http\Controllers\Admin\AssurancesOrderController;
use App\Http\Controllers\Admin\ViolationController;
use App\Http\Controllers\Admin\ContactsController;
use App\Http\Controllers\Admin\HouseKeeperHourlyOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
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


    Route::group(['middleware' => ['auth', 'admin'],'prefix' => 'admin'], function () {

        Route::view('/', 'dashboard.index')->name('admin.home');

        Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
            Route::view('/', 'dashboard.settings')->name('index');
            Route::post('/settings', [HomeController::class, 'settings'])->name('update');



            Route::view('/banners', 'dashboard.banners.banners')->name('banner');
            Route::get('/banners/{id}', [HomeController::class, 'banners'])->name('banners');
            Route::post('update/banners/{id}', [HomeController::class, 'bannersUpdate']);
            Route::post('banners/save', [HomeController::class, 'store'])->name('banners.store');
            Route::post('delete', [HomeController::class, 'bannersDelete'])->name('banners.delete');

        });

        Route::controller( CompanyController::class)->group(function () {
            Route::group(['as' => 'companies.', 'prefix' => 'companies'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/fetch/{id}', 'fetch')->name('fetch');
                Route::get('/view/{id}', 'view')->name('view');
                Route::get('/housekeepers/{id}', 'housekeepers')->name('housekeepers');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');

            });
        });


        Route::controller( UserController::class)->group(function () {
            Route::group(['as' => 'users.', 'prefix' => 'users'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/fetch/{id}', 'fetch')->name('fetch');
                Route::get('/view/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');
                Route::post('/notify', 'notify')->name('notify');
                Route::post('/notify/all-devices', 'sendNotificationToUsers')->name('sendNotificationToUsers');
                Route::POST('/change-status/{id}', 'changeStatus')->name('changeStatus');

            });
        });



        Route::controller( HouseKeeperController::class)->group(function () {
            Route::group(['as' => 'housekeepers.', 'prefix' => 'housekeepers'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/fetch/{id}', 'fetch')->name('fetch');
                Route::get('/view/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');

            });
        });



        Route::controller( HouseKeeperOrderController::class)->group(function () {
            Route::group(['as' => 'housekeepers.orders.', 'prefix' => 'housekeepers/orders'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/active', 'active')->name('active');
                Route::get('/complete', 'complete')->name('complete');
                Route::get('/expiring', 'expiring')->name('expiring');
                Route::get('/close', 'close')->name('close');
                Route::get('/list', 'list')->name('list');
                Route::get('/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');
                Route::post('status', 'updateStatus')->name('updateStatus');
                Route::post('/sendSms', 'sendSms')->name('sendSms');
                Route::get('get-housekeepers/{id}', 'getHousekeepers')->name('get-housekeepers');



            });
        });

        Route::controller( HouseKeeperHourlyOrderController::class)->group(function () {
            Route::group(['as' => 'housekeepers.HourlyOrders.', 'prefix' => 'housekeepers/HourlyOrders'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::get('/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');
                Route::post('status', 'updateStatus')->name('updateStatus');
                Route::get('print/{id}', 'print')->name('print');
                Route::get('get-housekeepers/{id}', 'getHousekeepers')->name('get-housekeepers');
                Route::post('/sendSms', 'sendSms')->name('sendSms');


            });
        });





        Route::controller( AssurancesController::class)->group(function () {
            Route::group(['as' => 'assurances.', 'prefix' => 'assurances'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/fetch/{id}', 'fetch')->name('fetch');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');
                Route::post('/status', 'status')->name('status');



            });
        });




        Route::controller( AssurancesOrderController::class)->group(function () {
            Route::group(['as' => 'assurances.orders.', 'prefix' => 'assurances/orders'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/negotiation', 'negotiation')->name('negotiation');
                Route::get('/under_payment', 'under_payment')->name('under_payment');
                Route::get('/payment', 'payment')->name('payment');
                Route::get('/completed', 'completed')->name('completed');
                Route::get('/cancelled', 'cancelled')->name('cancelled');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');
                Route::get('/files/{id}', 'files')->name('files');
                Route::post('status', 'updateStatus')->name('updateStatus');
                Route::post('/sendSms', 'sendSms')->name('sendSms');

            });
        });







        Route::controller( ContactsController::class)->group(function () {
            Route::group(['as' => 'contacts.', 'prefix' => 'contacts'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/fetch/{id}', 'fetch')->name('fetch');
                Route::post('/delete', 'destroy')->name('delete');

            });
        });


    Route::controller( ViolationController::class)->group(function () {
            Route::group(['as' => 'violations.', 'prefix' => 'violations'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/under_payed', 'under_payed')->name('under_payed');
                Route::get('/payed', 'payed')->name('payed');
                Route::get('/completed', 'completed')->name('completed');
                Route::get('/cancelled', 'cancelled')->name('cancelled');
                Route::get('/list', 'list')->name('list');
                Route::get('/{id}', 'view')->name('view');
                Route::post('/delete', 'destroy')->name('delete');
                Route::post('/update', 'update')->name('update');
                Route::post('status', 'updateStatus')->name('updateStatus');
                Route::post('/sendSms', 'sendSms')->name('sendSms');

            });
        });





        Route::group(['as' => 'profile.', 'prefix' => 'profile'], function () {
            Route::view('/', 'dashboard.profile')->name('index');
            Route::post('/update', [HomeController::class, 'profile'])->name('update');
            Route::post('/password', [HomeController::class, 'password'])->name('password');

        });

    });
});






Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return redirect()->route('home');
});






