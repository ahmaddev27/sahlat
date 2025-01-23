<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    public function index()
    {

        return view('dashboard.companies.index');
    }


    public function list(Request $request)
    {

        $query = Company::query();


        if ($request->has('city') && $request->city !== null) {
            $query->where('address', $request->city);
        }

        $companies=$query->get();

        return DataTables::of($companies)
            ->addColumn('housekeepers_count', function ($item) {
                return '<a data-toggle="modal"  model_id="' . $item->id . '" id="housekeeper"><div class="badge badge-glow badge-info">' . $item->housekeepers->count() . '</div></a>';

            })
            ->editColumn('name', function ($item) {
                return '                                   <img src="' . $item->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer" height="60" width="60" />

                                ' . $item->name;


            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d D');
            })


            ->editColumn('address', function ($item) {
                return $item->address ? cities($item->address) : '-';
            })


            ->editColumn('action', function ($item) {
                return '
                    <a href="'.route('companies.view',$item->id).'" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
               title="view">

            <i class="fa fa-eye text-body"></i>
        </a>
        <button type="button" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
                id="edit" model_id="' . $item->id . '" data-toggle="modal" title="edit">
            <i class="fa fa-edit text-body"></i>
        </button>

        <button type="button" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
                id="delete" route="' . route('companies.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="delete">
            <i class="fa fa-trash text-body"></i>
        </button>
    ';
            })

            ->addIndexColumn()
            ->rawColumns(['action', 'housekeepers_count', 'name','address'])
            ->make(true);

    }



   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:companies,email',
        'address' => 'required',
        'phone' => 'required|unique:companies|regex:/^[5][0-9]{8}$/',
        'experience' => 'required',
        'password' => 'required|string|min:8',
        'avatar' => 'required|image',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        $avatarPath = $request->file('avatar')->store('companies', 'public');

        Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'experience' => $request->experience,
            'phone' => $request->phone,
            'long' => $request->long,
            'lat' => $request->lat,
            'bio' => $request->bio,
            'password' => Hash::make($request->password),
            'avatar' => $avatarPath,
            'hourly_price' => $request->hourly_price,
        ]);

        return response()->json(['success' => true, 'message' => trans('messages.success-created')]);
    } catch (\Exception $e) {
        \Log::error('Error in store: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
    }
}


    public function fetch($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    public function view($id)
    {
        $company = Company::with(['services','views','housekeepers'])->findOrFail($id);
        return view('dashboard.companies.view',['company'=>$company]);
    }

    public function housekeepers($id)
    {
        $company = Company::findOrFail($id);

        $housekeepers = $company->housekeepers()->get()->map(function ($housekeeper) {
            $housekeeper->avatar_url = $housekeeper->getAvatar();
            $housekeeper->status_text = HouseKeepersStatus($housekeeper->status); // Use helper function for status
            $housekeeper->type_text = trans('housekeeper.'.$housekeeper->type); // Use helper function for status
            return $housekeeper;
        });

        return response()->json($housekeepers);
    }


    public function update(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:companies,email,' . $request->id,
        'address' => 'required',
        'experience' => 'required',
        'phone' => 'required|regex:/^[5][0-9]{8}$/|unique:companies,phone,' . $request->id,
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        $company = Company::findOrFail($request->id);

        if ($request->hasFile('avatar')) {
            if ($company->avatar && Storage::disk('public')->exists($company->avatar)) {
                Storage::disk('public')->delete($company->avatar);
            }
            $avatarPath = $request->file('avatar')->store('companies', 'public');
        }

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'experience' => $request->experience,
            'phone' => $request->phone,
            'long' => $request->long,
            'lat' => $request->lat,
            'bio' => $request->bio,
            'hourly_price' => $request->hourly_price,
            'password' => $request->filled('password') ? Hash::make($request->password) : $company->password,
            'avatar' => $request->hasFile('avatar') ? $avatarPath : $company->avatar,
        ]);

        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);
    } catch (\Exception $e) {
        \Log::error('Error in update: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
    }
}


   public function destroy(Request $request)
{
    try {
        $company = Company::findOrFail($request->id);

        if ($company->avatar && Storage::disk('public')->exists($company->avatar)) {
            Storage::disk('public')->delete($company->avatar);
        }

        $company->delete();

        return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
    } catch (\Exception $e) {
        \Log::error('Error in destroy: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
    }

}

}
