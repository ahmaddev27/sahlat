<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

    }


    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.home')
                ->withErrors(['error' => trans('messages.401')]);
        }

        if (Auth::guard('company')->check()) {
            return redirect()->route('company.home')
                ->withErrors(['error' => trans('messages.401')]);
        }


        return view('auth.login');
    }



    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if ($request->company) {
            // Attempt login with 'company' guard
            if (Auth::guard('company')->attempt($credentials, $remember)) {
                return response()->json(['message' => trans('messages.welcome')], 200);
            } else {
                return response()->json(['errors' => ['login' => trans('messages.company_invalid')]], 401);
            }
        } else {
            // Attempt login with 'admin' guard
            if (Auth::guard('web')->attempt($credentials, $remember)) {
                return response()->json(['message' => trans('messages.welcome')], 200);
            } else {
                return response()->json(['errors' => ['login' => trans('messages.admin_invalid')]], 401);
            }
        }
    }





    public function logout(Request $request)
    {
        if (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
        } else {
            Auth::logout();
        }

        return redirect()->route('home');
    }

}
