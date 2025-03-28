<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\FcmToken;
use Kreait\Firebase\Messaging\Notification;
use App\Notifications\GerenalNotification;
use Kreait\Firebase\Factory;
use App\Notifications\NewsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Messaging\CloudMessage;
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

        $users = $query->get();
        return DataTables::of($users)
            ->editColumn('name', function ($item) {
                return '<img src="' . $item->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer" height="60" width="60" />
                              ' . $item->name;
            })
            ->editColumn('checkbox', function ($item) {
                return '<input type="checkbox" class="user-checkbox" value="' . $item->id . '">';
            })
            ->editColumn('action', function ($item) {
                $statusChecked = $item->is_active == 1 ? 'checked' : '';
                $statusValue = $item->is_active == 1 ? '0' : '1';

                return '<div class="custom-control custom-switch custom-switch-primary d-inline">
            <input type="checkbox"
                   class="custom-control-input change-status-btn"
                   id="customSwitch' . $item->id . '"
                   data-id="' . $item->id . '"
                   data-status="' . $statusValue . '"
                   ' . $statusChecked . '>
            <label class="custom-control-label" for="customSwitch' . $item->id . '">
                <span class="switch-icon-left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </span>
                <span class="switch-icon-right">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </label>
        </div>
        <a href="' . route('users.view', $item->id) . '" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light" title="View">
            <i class="fa fa-eye text-body"></i>
        </a>
<button type="button" class="edit-user btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
    data-model-id="' . $item->id . '" data-toggle="modal" title="Edit">
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
            ->editColumn('status', function ($item) {
                $badgeClass = $item->is_active == 1 ? 'info' : 'warning';
                return '<div class="badge badge-pill badge-light-' . $badgeClass . '">' . user_statuts($item->is_active) . '</div>';
            })
            ->editColumn('location', function ($item) {
                return $item->location ? cities($item->location) : '-';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'name', 'gender', 'phone', 'location', 'checkbox', 'number_id', 'status'])
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

        try {
            $avatarPath = $request->file('avatar')->store('Users', 'public');

            AppUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'location' => $request->city,
                'gender' => $request->gender,
                'number_id' => $request->number_id,
                'avatar' => $avatarPath,
            ]);

            return response()->json(['success' => true, 'message' => trans('messages.success-created')]);
        } catch (\Exception $e) {
            \Log::error('Error in store method: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
        }
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

        try {
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
                'avatar' => $request->hasFile('avatar') ? $avatarPath : $user->avatar,
            ]);

            return response()->json(['success' => true, 'message' => trans('messages.success-update')]);
        } catch (\Exception $e) {
            \Log::error('Error in update method: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
        }
    }


    public function destroy(Request $request)
    {
        try {
            $user = AppUser::findOrFail($request->id);

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();

            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        } catch (\Exception $e) {
            \Log::error('Error in destroy method: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
        }
    }


    public function changeStatus(Request $request)
    {
        try {
            $user = AppUser::findOrFail($request->id);
            $user->is_active = $request->status;
            $user->save();

            return response()->json(['message' => trans('messages.status-change-success'), 'status' => true], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        } catch (\Exception $e) {
            \Log::error('Error in changeStatus method: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
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



    public function sendNotificationToUsers(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        // Get all device tokens from the database
        $tokens = FcmToken::all()->pluck('token')->toArray();

        // Initialize Firebase Messaging
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/sahalat.json'))
            ->createMessaging();

        // Prepare the notification
        $notification = Notification::create($request->title, $request->message);

        $response = [];

        // Loop through all tokens and send notifications
        foreach ($tokens as $token) {
            try {
                // Create CloudMessage for a specific token
                $message = CloudMessage::new()
                    ->withNotification($notification)
                    ->withData(['type' => 'general'])
                    ->toToken($token); // <-- Use the dedicated method

                // Send the notification
                $firebase->send($message);

                // Log success for each token
                Log::info("Notification sent to token: $token");

                // Add success response for this token
                $response[] = [
                    'token' => $token,
                    'status' => 'Notification sent successfully'
                ];

            } catch (\Exception $e) {
                // Log failure for each token
                Log::error("Failed to send notification to token: $token, Error: " . $e->getMessage());

                // Add failure response for this token
                $response[] = [
                    'token' => $token,
                    'status' => 'Failed',
                    'error' => $e->getMessage(),
                ];
            }
        }

        // Return response with all statuses
        return response()->json([
            'message' => 'Notification sent to all users!',
            'status' => $response
        ]);
    }
}
