<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Notification;
use App\Notifications\NewsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {

        return view('dashboard.users.index');
    }


    public function list(Request $request)
    {
        $query = AppUser::query();

        if ($request->has('city') && $request->city !== null) {
            $query->where('location', $request->city);
        }

        $users=$query->get();
        return DataTables::of($users)
            ->editColumn('name', function ($item) {
                return '<img src="' . $item->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer" height="60" width="60" />
                              ' . $item->name;
            })

            ->editColumn('checkbox', function ($item) {
                return '<input type="checkbox" class="user-checkbox" value="' . $item->id . '">';
            })





            ->editColumn('action', function ($item) {
                return '
                 <a href="' . route('users.view', $item->id) . '" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
               title="view">

            <i class="fa fa-eye text-body"></i>
        </a>


        <button type="button" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
                id="edit" model_id="' . $item->id . '" data-toggle="modal" title="edit">
            <i class="fa fa-edit text-body"></i>
        </button>

        <button type="button" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
                id="delete" route="' . route('users.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="delete">
            <i class="fa fa-trash text-body"></i>
        </button>
    ';
            })
            ->editColumn('gender', function ($item) {
                return gender($item->gender);
            })
            ->editColumn('location', function ($item) {
                return $item->location ? cities($item->location) : '-';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'name', 'gender', 'phone','location', 'checkbox','number_id'])
            ->make(true);

    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'city' => 'required',
            'email' => 'required|email|unique:app_users,email',
            'phone' => 'required|regex:/^[5][0-9]{8}$/|unique:app_users,phone',
            'gender' => 'required',
            'number_id' => ['required', 'regex:/^784-\d{4}-\d{7}-\d{1}$/', 'unique:app_users,number_id'],
            'avatar' => 'required|image',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $avatarPath = $request->file('avatar')->store('Users', 'public');


        AppUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->city,
            'gender' => $request->gender,
            'number_id' => $request->number_id,
//            'password' => Hash::make($request->password),
            'avatar' => $avatarPath,
        ]);
        return response()->json(['success' => true, 'message' => trans('messages.success-created')]);

    }


    public function fetch($id)
    {
        $user = AppUser::findOrFail($id);
        return response()->json($user);
    }


    public function view($id)
    {
        $user = AppUser::with(['payments', 'violation', 'houseKeeperOrder', 'assuranceOrder'])->findOrFail($id);
        return view('dashboard.users.view', ['user' => $user]);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:15',
            'city' => 'required',
            'number_id' => ['required', 'regex:/^784-\d{4}-\d{7}-\d{1}$/', 'unique:app_users,number_id,' . $request->id],
            'phone' => ['required', 'regex:/^[5][0-9]{8}$/', 'unique:app_users,phone,' . $request->id],
            'email' => 'required|email|unique:app_users,email,' . $request->id,
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $user = AppUser::findOrFail($request->id);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('Users', 'public');
        }

        $user->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'number_id' => $request->number_id,
            'email' => $request->email,
            'location' => $request->city,
            'phone' => $request->phone,
//            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            'avatar' => $request->hasFile('avatar') ? $avatarPath : $user->avatar,
        ]);

        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);

    }


    public function destroy(Request $request)
    {
        $user = AppUser::find($request->id);

        if ($user) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->delete();

            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
        } else {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        }
    }

    public function notify(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:app_users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $userIds = $validated['user_ids'];
        $title = $validated['title'];
        $message = $validated['message'];
        $type = 'general';
        $image = setting('icon');


        // Batch insert notifications
        $notifications = array_map(fn($userId) => [
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'image' => $image,
            'created_at' => now(),
            'updated_at' => now(),
        ], $userIds);

        DB::table('notifications')->insert($notifications);


        $users = AppUser::whereIn('id', $userIds)->get();
        foreach ($users as $user) {
            $user->notify(new NewsNotification($title, $message, $type));
        }

        return response()->json([
            'success' => true,
            'message' => trans('messages.success-notify'),
        ]);
    }


}
