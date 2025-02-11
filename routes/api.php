<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Orders\ViolationController;
use App\Http\Controllers\Api\Orders\AssuranceController;
use App\Http\Controllers\Api\Orders\HouseKeeperController;
use App\Http\Controllers\Api\Orders\HouseKeeperHourlyController;
use App\Http\Controllers\Api\ReviewController;


Route::get('/langs', function () {

    $array = array_map(function ($name, $id) {
        return ['id' => $id, 'name' => $name];
    }, getAllLangs(), array_keys(getAllLangs()));

    return response([
        'data' => $array,
        'message' => trans('messages.success'), // Success message
        'status' => true, // Success status
        'code' => 200 // HTTP status code

    ]);

});


Route::controller(AuthController::class)->group(function () {
    Route::post('/send-otp', 'sendOtp');
    Route::post('/verify-otp', 'verifyOtp');

});


Route::group(['middleware' => 'auth:sanctum'], function () {


    Route::controller(AuthController::class)->group(function () {
        Route::get('/religions', function () {

            $array = array_map(function ($name, $id) {
                return ['id' => $id, 'name' => $name];
            }, getAllReligions(), array_keys(getAllReligions()));


            return response([
                'data' => $array,
                'message' => trans('messages.success'), // Success message
                'status' => true, // Success status
                'code' => 200 // HTTP status code

            ]);


        });


        Route::get('/nationalities', function () {

            $array = array_map(function ($name, $id) {
                return ['id' => $id, 'name' => $name];
            }, Nationalities(), array_keys(Nationalities()));


            return response([
                'data' => $array,
                'message' => trans('messages.success'), // Success message
                'status' => true, // Success status
                'code' => 200 // HTTP status code

            ]);


        });


        Route::get('/cites', function () {

            $array = array_map(function ($name, $id) {
                return ['id' => $id, 'name' => $name];
            }, cities(), array_keys(cities()));


            return response([


                'status' => true, // Success status
                'code' => 200,
                'message' => trans('messages.success'), // Success message
                'data' => $array,

            ]);


        });

        Route::post('/change-lang', 'changLang');

        Route::post('/updateProfile', 'updateProfile');
        Route:: post('/logout', 'logout');
        Route:: post('/logout', 'logout');
        Route:: get('/profile', 'profile');


        Route::post('/sendOtpOldPhone', 'sendOtpOldPhone');
        Route::post('/verifyOtpOldPhone', 'verifyOldPhoneOtp');
        Route::post('/sendOtpNewPhoneRequest', 'sendOtpToNewPhone');
        Route::post('/verifyNewPhoneOtp', 'verifyNewPhoneOtp');


    });


    Route::controller(HomeController::class)->group(function () {
        Route::get('/banners', 'banners');
        Route::get('/assurances', 'assurances');
        Route::get('/companies', 'companies');
        Route::get('/company/{id}', 'company');
        Route::get('/company/housekeepers/{id}', 'housekeepersCompany');
        Route::get('/housekeepers', 'housekeepers');
        Route::get('/housekeeper/{id}', 'housekeeper');
        Route::get('/settings', 'settings');
        Route::post('/contact', 'contact');
        Route::post('/search', 'search');
        Route::get('/topRatedHousekeeper', 'topRatedHousekeeper');
        Route::get('/mostOrderedAssurances', 'mostOrderedAssurances');
        Route::get('/notifications', 'notification');

    });


    Route::controller(ViolationController::class)->group(function () {
        Route::group(['middleware' => 'is_active'], function () {
            Route::post('/OrderViolation', 'OrderViolation');
        });
    });

    Route::controller(AssuranceController::class)->group(function () {
        Route::group(['middleware' => 'is_active'], function () {
            Route::post('/OrderAssurance', 'OrderAssurance');
        });
    });
    Route::controller(HouseKeeperController::class)->group(function () {
        Route::group(['middleware' => 'is_active'], function () {
            Route::post('/housekeeperOrder', 'housekeeperOrder');
        });
    });

    Route::controller(HouseKeeperHourlyController::class)->group(function () {
        Route::group(['middleware' => 'is_active'], function () {
            Route::post('/housekeeperHourlyOrder', 'housekeeperHourlyOrder');
        });
    });


    Route::controller(OrderController::class)->group(function () {
        Route::post('/payTabby', 'payTabby');
        Route::get('/balance', 'balance');


        Route::get('/getHouseKeeperOrder/{id}', 'getHouseKeeperOrder')->name('api.housekeeperRecords');
        Route::get('/getHourlyHouseKeeperOrder/{id}', 'getHourlyHouseKeeperOrder')->name('api.housekeeper_hourly_orderRecords');
        Route::get('/getAssuranceOrder/{id}', 'getAssuranceOrder')->name('api.assuranceRecords');
        Route::get('/violationRecords/{id}', 'getAssuranceOrder')->name('api.violationRecords');

        Route::get('/records/assurances', 'assurancesRecords');
        Route::get('/records/housekeepers', 'housekeepersRecords');
        Route::get('/records/housekeepersHourly', 'housekeepersHourlyRecords');
        Route::get('/records/violations', 'violationsRecords');


        Route::group(['middleware' => 'is_active'], function () {
            Route::post('/cancelHousekeeperOrder', 'cancelHousekeeperOrder');

        });
    });


    Route::middleware('is_active')->controller(ReviewController::class)->group(function () {
        Route::post('/housekeeperReview', 'housekeeperReview');


    });


});







