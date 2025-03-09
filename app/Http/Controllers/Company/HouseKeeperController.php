<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\HouseKeeper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class HouseKeeperController extends Controller
{
    public function index()
    {

        return view('company.housekeepers.index');
    }


    public function list(Request $request)
    {

        $houseKeeperQuery = HouseKeeper::where('company_id',auth('company')->id());
        if ($request->has('status') && $request->status !== null) {
            $houseKeeperQuery->where('status', $request->status);
        }

        if ($request->has('gender') && $request->gender !== null) {
            $houseKeeperQuery->where('gender', $request->gender);
        }



        if ($request->has('search') && $request->search['value']) {


            $search = $request->search['value'];
            $houseKeeperQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('salary', 'like', "%$search%");
            });
        }


        $houseKeeper=$houseKeeperQuery->get();


        return DataTables::of($houseKeeper)
            ->addColumn('status', function ($item) {
                $statusText = HouseKeepersStatus($item->status);
                $badgeClass = $item->status == 1 ? 'primary' : 'success';

                return '<div class="badge badge-light-' . $badgeClass . '">' . $statusText . '</div>';
            })


            ->editColumn('name', function ($item) {
                return '<img src="' . $item->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded cursor-pointer m-1" height="60" width="60" />
                              ' . $item->name;
            })
            ->editColumn('gender', function ($item) {
                return gender($item->gender);
            })

//
//            ->addColumn('type', function ($item) {
//                return trans('housekeeper.'.$item->type ).'</div>';
//
//            })

            ->editColumn('action', function ($item) {
                return '

                              <a href="'.route('company.housekeepers.view',$item->id).'" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
               title="view">

            <i class="fa fa-eye text-body"></i>
        </a>
        <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light edit-housekeeper"
                id="edit" data-id="' . $item->id . '"  title="Edit">
            <i class="fa fa-edit text-body"></i>
        </button>

        <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                id="delete" route="' . route('company.housekeepers.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="Delete">
            <i class="fa fa-trash text-body"></i>
        </button>
    ';
            })

            ->addIndexColumn()
            ->rawColumns(['action', 'type', 'name','status','company','gender'])
            ->make(true);

    }



    public function store(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
//            'company' => 'required',
            'language' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'description' => 'required',
            'phone' => 'required|regex:/^[5][0-9]{8}$/|unique:housekeepers,phone',

            'experience' => 'required',
//            'type' => 'required',
            'salary' => 'required',
            'avatar' => 'required|image',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $avatarPath = $request->file('avatar')->store('Housekeepers', 'public');


        HouseKeeper::create([
            'name' => $request->name,
            'company_id' => auth('company')->id(),
            'nationality' => $request->nationality,
            'language' => $request->language,
            'religion' => $request->religion,
            'phone' => $request->phone,
            'description' => $request->description,
            'experience' => $request->experience,
//                'type' => $request->type,
            'salary' => $request->salary,
            'gender' => $request->gender,

            'avatar' => $avatarPath,
        ]);


        return response()->json(['success' => true, 'message' => trans('messages.success-created')]);


    }




    public function fetch($id)
    {
        $housekeeper = HouseKeeper::find($id); // Adjust this line according to your model and logic

        if (!$housekeeper) {
            return response()->json(['message' => trans('messages.not-found')], 404);
        }

        return response()->json($housekeeper);
    }


    public function view($id)
    {
        $housekeeper = HouseKeeper::with(['company','reviews','orderd'])->findOrFail($id);
        if ($housekeeper->company_id == auth('company')->id()){
            return view('company.housekeepers.view',['housekeeper'=>$housekeeper]);


        }

        return redirect()->back()->withErrors(['error' => trans('messages.401')]);
    }




    public function update(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
//            'company' => 'required',
            'phone' => 'required|regex:/^[5][0-9]{8}$/|unique:housekeepers,phone,'. $request->housekeeper_id,

            'language' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'experience' => 'required',
            'description' => 'required',


//            'type' => 'required',
            'salary' => 'required',
            'avatar' => 'nullable|image',


        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $housekeeper = HouseKeeper::findOrFail($request->housekeeper_id);
        $currentAvatarPath = $housekeeper->avatar;
        // Update the housekeeper details
        $housekeeper->update([
            'name' => $request->name,
            'company_id' => auth('company')->id(),

            'nationality' => $request->nationality,
            'language' => $request->language,
            'religion' => $request->religion,
            'phone' => $request->phone,
            'experience' => $request->experience,
            'description' => $request->description,
//                'type' => $request->type,
            'salary' => $request->salary,
            'gender' => $request->gender,

//                'avatar' => $avatarPath,
        ]);

        // Handle avatar upload if it exists
        if ($request->hasFile('avatar')) {
            // Delete the old avatar if it exists
            if ($currentAvatarPath && Storage::disk('public')->exists($currentAvatarPath)) {
                Storage::disk('public')->delete($currentAvatarPath);
            }
            // Store the new avatar
            $avatarPath = $request->file('avatar')->store('Housekeepers', 'public');
            // Update the avatar path in the database
            $housekeeper->update(['avatar' => $avatarPath]);
        }

        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);

    }


    public function destroy(Request $request)
    {
        $housekeeper = HouseKeeper::find($request->id);

        if ($housekeeper) {
            if ($housekeeper->avatar && Storage::disk('public')->exists($housekeeper->avatar)) {
                Storage::disk('public')->delete($housekeeper->avatar);
            }
            $housekeeper->delete();

            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
        } else {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        }
    }


}
