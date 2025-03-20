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

/**
 * Helper to convert associative arrays to a list of id-name pairs.
 *
 * @param array $items
 * @return array
 */
if (!function_exists('transformToIdNameArray')) {
    function transformToIdNameArray(array $items): array
    {
        return array_map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        }, $items, array_keys($items));
    }
}

/**
 * Helper to return a standardized API response.
 *
 * @param mixed $data
 * @param string $message
 * @param int $code
 * @param bool $status
 * @return \Illuminate\Http\Response
 */
if (!function_exists('apiResponse')) {
    function apiResponse($data, string $message = '', int $code = 200, bool $status = true)
    {
        return response([
            'data' => $data,
            'message' => $message,
            'status' => $status,
            'code' => $code,
        ]);
    }
}

// ==================================================================
// Public Routes
// ==================================================================

Route::get('/langs', function () {
    $langs = transformToIdNameArray(getAllLangs());
    return apiResponse($langs, trans('messages.success'));
});

Route::get('/nationalities', function () {
    $nationalities = transformToIdNameArray(Nationalities());
    return apiResponse($nationalities, trans('messages.success'));
});

Route::get('/cites', function () {
    $cities = transformToIdNameArray(cities());
    return apiResponse($cities, trans('messages.success'));
});

Route::get('/religions', function () {
    $religions = transformToIdNameArray(getAllReligions());
    return apiResponse($religions, trans('messages.success'));
});

// Auth Routes for non-authenticated users.
Route::controller(AuthController::class)->group(function () {
    Route::post('/send-otp', 'sendOtp');
    Route::post('/verify-otp', 'verifyOtp');
});

// ==================================================================
// Authenticated Routes (requires sanctum auth)
// ==================================================================

//Route::middleware('auth:sanctum')->group(function () {
// -------------------------------
// AuthController Routes
// -------------------------------

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {

        Route::post('/change-lang', 'changLang');
        Route::post('/updateProfile', 'updateProfile');
        Route::post('/logout', 'logout');
        Route::get('/profile', 'profile');

        Route::post('/sendOtpOldPhone', 'sendOtpOldPhone');
        Route::post('/verifyOtpOldPhone', 'verifyOldPhoneOtp');
        Route::post('/sendOtpNewPhoneRequest', 'sendOtpToNewPhone');
        Route::post('/verifyNewPhoneOtp', 'verifyNewPhoneOtp');
    });
});

// -------------------------------
// HomeController Routes
// -------------------------------
Route::controller(HomeController::class)->group(function () {
    Route::get('/banners', 'banners');
    Route::get('/assurances', 'assurances');
    Route::get('/companies', 'companies');
    Route::get('/company/{id}', 'company');
    Route::get('/company/housekeepers/{id}', 'housekeepersCompany');
    Route::get('/housekeepers', 'housekeepers');
    Route::get('/housekeeper/{id}', 'housekeeper');
    Route::get('/settings', 'settings');
    Route::post('/search', 'search');
    Route::get('/topRatedHousekeeper', 'topRatedHousekeeper');
    Route::get('/mostOrderedAssurances', 'mostOrderedAssurances');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/notifications', 'notification');
        Route::post('/contact', 'contact');
    });
});

// -------------------------------
// Order-Related Routes (requires active status)
// -------------------------------

Route::middleware(['auth:sanctum', 'is_active'])->group(function () {

    // Violation Orders
    Route::controller(ViolationController::class)->group(function () {
        Route::post('/OrderViolation', 'OrderViolation');
        Route::get('/getViolationOrder/{id}', 'getViolationOrder')->name('api.violationRecords');
        Route::get('/records/violations', 'violationsRecords');
    });

    // Assurance Orders
    Route::controller(AssuranceController::class)->group(function () {
        Route::post('/OrderAssurance', 'OrderAssurance');
        Route::get('/records/assurances', 'assurancesRecords');
        Route::get('/getAssuranceOrder/{id}', 'getAssuranceOrder')->name('api.assuranceRecords');
    });

    // HouseKeeper Orders
    Route::controller(HouseKeeperController::class)->group(function () {
        Route::post('/housekeeperOrder', 'housekeeperOrder');
        Route::get('/getHouseKeeperOrder/{id}', 'getHouseKeeperOrder')->name('api.housekeeperRecords');
        Route::get('/records/housekeepers', 'housekeepersRecords');
    });

    // HouseKeeper Hourly Orders
    Route::controller(HouseKeeperHourlyController::class)->group(function () {
        Route::post('/housekeeperHourlyOrder', 'housekeeperHourlyOrder');
        Route::get('/records/housekeepersHourly', 'housekeepersHourlyRecords');
        Route::get('/getHourlyHouseKeeperOrder/{id}', 'getHourlyHouseKeeperOrder')->name('api.housekeeper_hourly_orderRecords');
    });

    // Review Routes
    Route::controller(ReviewController::class)->group(function () {
        Route::post('/housekeeperReview', 'housekeeperReview');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::post('/payTabby', 'payTabby');
        Route::get('/balance', 'balance');
        Route::post('/stripe-check', 'checkOrderStatusStripe');
        Route::post('/deleteFailedOrder', 'deleteFailedOrder');
    });


});
