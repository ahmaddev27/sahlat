<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Assurance;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AssurancesController extends Controller
{

    public function index()
    {
        return view('dashboard.assurances.index');
    }

    public function list(Request $request)
    {
        $query = Assurance::query();

        // Filter by status if provided
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addColumn('status', function ($item) {
                $statusChecked = $item->status == 1 ? 'checked' : '';
                $route = route('assurances.status', $item->id);

                return '<div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input status-toggle"
                    id="customSwitch' . $item->id . '"
                    ' . $statusChecked . '
                    model_id="' . $item->id . '"
                    route="' . $route . '"
                    style="cursor: pointer;">
                <label class="custom-control-label" for="customSwitch' . $item->id . '">
                      <span class="switch-icon-left"><i data-feather="x"></i></span>
                      <span class="switch-icon-right"><i data-feather="check"></i></span>
                </label>
            </div>';
            })
            ->editColumn('title', function ($item) {
                return '<img src="' . $item->getAvatar() . '" alt="avatar" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer m-2" height="60" width="60" />
                    ' . $item->title;
            })
            ->editColumn('description', function ($item) {
                return str_limit($item->description);
            })
            ->editColumn('action', function ($item) {
                return '
                <button type="button" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light edit-service"
                        id="edit" data-id="' . $item->id . '" title="Edit">
                    <i class="fa fa-edit"></i>
                </button>
                <button type="button" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light delete-service"
                        id="delete" route="' . route('assurances.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            ';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'title', 'status', 'description'])
            ->make(true);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required',
            'company' => 'required',
            'company_logo' => 'required',
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $avatarPath = $request->file('image')->store('assurances', 'public');
        $logoPath = $request->file('company_logo')->store('assurances', 'public');


        Assurance::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $avatarPath,
            'company_logo' => $logoPath,
            'company' => $request->company,
            'price' => $request->price,

        ]);


        return response()->json(['success' => true, 'message' => trans('messages.success-created')]);


    }


    public function fetch($id)
    {
        $assurances = Assurance::find($id);

        if (!$assurances) {
            return response()->json(['message' => trans('messages.not-found')], 404);
        }

        $assurances->image_url = $assurances->getAvatar();
        $assurances->company_logo = $assurances->getCompanyAvatar();
        return response()->json($assurances);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required',
            'company' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = Assurance::findOrFail($request->id);



        if ($request->hasFile('image')) {
            if ($service->avatar && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
            $avatarPath = $request->file('image')->store('assurances', 'public');
        }

      if ($request->hasFile('company_logo')) {
            if ($service->avatar && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
          $LogoPath = $request->file('company_logo')->store('assurances', 'public');
        }



        $service->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'company' => $request->company,
            'image' => $request->hasFile('image') ? $avatarPath : $service->image,
            'company_logo' => $request->hasFile('company_logo') ? $LogoPath : $service->company_logo,
        ]);

            return response()->json(['success' => true, 'message' => trans('messages.success-update')]);

    }


    public function status(Request $request)
    {
        $item = Assurance::find($request->id);

        if ($item) {
            $item->status = !$item->status; // Toggle status
            $item->save();

            return response()->json([
                'success' => true,
                'new_status' =>AssuranceStatus($item->status),
                'status_text' =>AssuranceStatus($item->status),
            ]);
        }

        return response()->json(['success' => false], 500);
    }


    public function destroy(Request $request)
    {
        $service = Assurance::find($request->id);

        if ($service) {
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
            $service->delete();

            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
        } else {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        }
    }





}
