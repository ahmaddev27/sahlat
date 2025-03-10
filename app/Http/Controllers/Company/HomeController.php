<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{


    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[5][0-9]{8}$/|unique:companies,phone,' . \auth()->id(),
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (Auth::guard('company')->check()) {
            $validator->addRules([
                'experience' => 'required|integer|min:0',
                'long' => 'required',
                'lat' => 'required',
            ]);
        }


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Update fields
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->experience = $request->input('experience');
        $user->long = $request->input('long');
        $user->lat = $request->input('lat');
        $user->bio = $request->input('bio');
        $user->hourly_price = $request->input('hourly_price');

        // Handle file upload for avatar, if present
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('companies', 'public');
            $user->avatar = $avatarPath;
        }

        // Save updated user details
        $user->save();

        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);
    }

    public function password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);


    }


}
