<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResources;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    use ApiResponseTrait;

    public function sendOtp(Request $request)
    {

        $rules = [
            'phone' => ['required', 'regex:/^[5][0-9]{8}$/'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }


        $phone = $request->phone;

        // Generate OTP
//        $otp = rand(100000, 999999);
        $otp = '1234';

        Cache::put("otp_{$phone}", $otp, now()->addMinutes(5));

        $user = AppUser::where('phone', $phone)->first();

        if ($user) {
            return $this->apiRespose(['otp' => $otp], trans('main.otp-login-send'), true, 200);

        } else {

            return $this->apiRespose(['otp' => $otp], trans('main.otp-register-send'), true, 200);

        }
    }

    public function verifyOtp(Request $request)
    {
        $rules = [
            'phone' => ['required', 'regex:/^[5][0-9]{8}$/'],
            'otp' => 'required',
            'fcm_token' => 'required', // Ensure fcm_token is part of the request
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

        $phone = $request->phone;
        $otp = $request->otp;
        $fcmToken = $request->fcm_token;

        // Check OTP validity
        $cachedOtp = Cache::get("otp_{$phone}");

        if ($cachedOtp && $cachedOtp == $otp) {

            $user = AppUser::where('phone', $phone)->first();

            if ($user) {
                // Check if the FCM token already exists in the user's fcm_tokens
                if (!$user->fcm_tokens()->where('token', $fcmToken)->exists()) {
                    // If the FCM token doesn't exist, create a new record
                    $user->fcm_tokens()->create([
                        'token' => $fcmToken,
                    ]);
                }

                return $this->apiRespose([
                    'token' => $user->createToken('ApiToken')->plainTextToken,
                    'user' => new UserResources($user),
                ], trans('main.login-success'), true, 200);

            } else {
                // Create a new user and associate the FCM token
                $user = AppUser::create([
                    'name' => $phone,
                    'phone' => $phone,
                    'profile_status' => '0',
                ]);

                // Store the FCM token for the new user
                $user->fcm_tokens()->create([
                    'token' => $fcmToken,
                ]);

                return $this->apiRespose([
                    'token' => $user->createToken('ApiToken')->plainTextToken,
                    'user' => new UserResources($user),
                ], trans('main.register-success'), true, 200);
            }


        } else {
            $errors[] = trans('main.incorrect-otp');
            return $this->apiRespose(['errors' => $errors], trans('main.incorrect-otp'), false, 400);
        }
    }


    public function sendOtpOldPhone(Request $request)
    {
        $user = $request->user(); // Authenticated user
        $oldPhone = $user->phone;

        // Generate OTP for old phone
        $oldPhoneOtp = '1234'; // Static OTP for testing, use rand(100000, 999999) for dynamic OTP
        Cache::put("update_otp_old_{$oldPhone}", $oldPhoneOtp, now()->addMinutes(5));

        // Simulate sending OTP to old phone (integrate SMS service for production)
        return $this->apiRespose(['otp' => $oldPhoneOtp], trans('main.otp-old-phone-send'), true, 200);
    }


    public function verifyOldPhoneOtp(Request $request)
    {
        $rules = [
            'otp' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(' ', array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose(['errors' => $errors], $errorMessage, false, 400);
        }

        $oldPhone = Auth::user()->phone;
        $otp = $request->otp;

        $cachedOldPhoneOtp = Cache::get("update_otp_old_{$oldPhone}");

        if ($cachedOldPhoneOtp && $cachedOldPhoneOtp === $otp) {
            return $this->apiRespose(['otp' => $otp], trans('main.ok'), true, 200);
        } else {
            $errors = [trans('main.incorrect-otp')];
            return $this->apiRespose(['errors' => $errors], trans('main.incorrect-otp'), false, 400);
        }
    }

    public function sendOtpToNewPhone(Request $request)
    {
        $rules = [
            'phone' => ['required', 'regex:/^[5][0-9]{8}$/', Rule::unique('app_users', 'phone')]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(' ', array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose(['errors' => $errors], $errorMessage, false, 400);
        }

        $newPhone = $request->phone;

        // Generate a dynamic OTP
//        $newPhoneOtp = rand(100000, 999999);
        $newPhoneOtp ='1234';
        Cache::put("update_otp_new_{$newPhone}", $newPhoneOtp, now()->addMinutes(5));

        // Simulate sending OTP (integrate with SMS service in production)
        return $this->apiRespose(['otp' => $newPhoneOtp], trans('main.otp-new-phone-send'), true, 200);
    }

    public function verifyNewPhoneOtp(Request $request)
    {
        $rules = [
            'phone' => ['required', 'regex:/^[5][0-9]{8}$/'],
            'otp' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(' ', array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose(['errors' => $errors], $errorMessage, false, 400);
        }

        $newPhone = $request->phone;
        $otp = $request->otp;

        $cachedNewPhoneOtp = Cache::get("update_otp_new_{$newPhone}");

        if ($cachedNewPhoneOtp && $cachedNewPhoneOtp === $otp) {
            return $this->updatePhone($request, $newPhone);
        } else {
            $errors = [trans('main.incorrect-otp')];
            return $this->apiRespose(['errors' => $errors], trans('main.incorrect-otp'), false, 400);
        }
    }

    public function updatePhone(Request $request, $newPhone)
    {
        $user = Auth::user();

        // Update the user's phone number
        $user->update(['phone' => $newPhone]);

        // Clear OTPs from cache
        Cache::forget("update_otp_old_{$user->phone}");
        Cache::forget("update_otp_new_{$newPhone}");

        return $this->apiRespose([
            'user' => new UserResources($user),
        ], trans('main.phone-update-success'), true, 200);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
//            'number_id' => ['required','regex:/^784-\d{4}-\d{7}-\d{1}$/', Rule::unique('app_users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('app_users')->ignore($user->id)],
//                'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

        if (!$user) {
            return $this->apiRespose([], 'User not authenticated', false, 401);
        }

        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->location = $request->location ?? $user->location;
        $user->gender = $request->gender ?? $user->gender;
        $user->number_id = $request->number_id ?? $user->number_id;
        $user->profile_status = 1;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }


        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('Users', 'public');
            $user->avatar = $avatarPath;
        }


        $user->save();
        return $this->apiRespose(
            new  UserResources(Auth::user()), trans('main.profile-update-success'), true, 200);

    }


    public function profile()
    {

        return $this->apiRespose(
            new UserResources(Auth::user()), trans('messages.success'), true, 200);

    }


    public function logout()
    {
        Auth::user()->tokens()->delete();

        return $this->apiRespose(
            [], trans('main.logout-success'), true, 200);

    }


    public function changlang(Request $request)
    {
        $request->validate([
            'lang' => 'required|in:en,ar',
        ]);

        $user = Auth::user();

        $user->lang = $request->lang;
        $user->save();

        app()->setLocale($user->lang);

        return $this->apiRespose(
            new UserResources(Auth::user()), trans('messages.success'), true, 200);
    }


}
