<?php

namespace App\Http\Controllers;

use App\Models\Banner;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Charge;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('home');
    }

    public function getPaymentHistory()
    {
        // Set your Stripe secret key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Retrieve payment history (charges)
            $charges = Charge::all([
                'limit' => 10, // you can modify the limit as needed
            ]);
            return response()->json($charges);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    public function getPaymentDetails($payment_id)
    {
        // Set your Stripe secret key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Retrieve the specific charge using the payment ID
            $charge = Charge::retrieve($payment_id);

            // Return the charge details
            return response()->json($charge);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.index');
    }


    public function home()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.home');
        }

        if (Auth::guard('company')->check()) {
            return 450;
        }

        return redirect()->route('login');
    }


    public function settings(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:15',
            'commission' => 'required',
            'email' => 'required|email',
            'about' => 'required|string|max:1000',
            'conditions' => 'required|string|max:1000',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'x' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);


        $settings = [
            'name' => $request->input('name'),
            'whatsapp' => $request->input('whatsapp'),
            'email' => $request->input('email'),
            'about' => $request->input('about'),
            'facebook' => $request->input('facebook'),
            'instagram' => $request->input('instagram'),
            'commission' => $request->input('commission'),
            'conditions' => $request->input('conditions'),
            'policy' => $request->input('policy'),
            'x' => $request->input('x'),
        ];


        foreach ($settings as $key => $value) {
            Settings::where('key', $key)->update(['value' => $value]);
        }

        if ($request->hasFile('logo')) {

            $oldlogo = setting('logo');
            if ($oldlogo && Storage::disk('public')->exists($oldlogo)) {
                Storage::disk('public')->delete($oldlogo);  // Delete the old icon
            }

            $logo = $request->file('logo')->store('logos', 'public');
            Settings::where('key', 'logo')->update(['value' => $logo]);
        }

        if ($request->hasFile('icon')) {

            $oldIcon = setting('icon');
            if ($oldIcon && Storage::disk('public')->exists($oldIcon)) {
                Storage::disk('public')->delete($oldIcon);  // Delete the old icon
            }


            $icon = $request->file('icon')->store('icons', 'public');
            Settings::where('key', 'icon')->update(['value' => $icon]);
        }

        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);

    }


    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Update fields
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Handle file upload for avatar, if present
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('Admins', 'public');
            $user->avatar = $avatarPath; // Assuming `avatar` is the column for storing avatar path
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


    public function banners($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['error' => 'Banner not found'], 404);
        }

        // Add image_url to the banner data
        $bannerData = $banner->toArray();
        $bannerData['image_url'] = $banner->getAvatar();

        return response()->json($bannerData);
    }

    public function bannersUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json(['error' => 'Banner not found'], 404);
        }

        $banner->title = $request->input('title');

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            $avatarPath = $request->file('image')->store('banners', 'public');
            $banner->image = $avatarPath;
        }

        $banner->save();

        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);
    }


    public function bannersDelete(Request $request)
    {

        $banner = Banner::find($request->id);

        if ($banner) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->delete();

            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
        } else {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        }

    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the banner
        $banner = new Banner();
        $banner->title = $request->input('title');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $banner->image = $imagePath;
        }

        $banner->save();

        return response()->json(['success' => true, 'message' => trans('messages.success-create')]);
    }


}
