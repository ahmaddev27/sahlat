<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyService;
use App\Models\HouseKeeper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    public function index()
    {
        return view('company.services.index');
    }


    public function list()
    {
        $houseKeeper = CompanyService::where('company_id',auth('company')->id())->get();



        return DataTables::of($houseKeeper)


            ->editColumn('action', function ($item) {
                return '
        <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light edit-housekeeper"
                id="edit" data-id="' . $item->id . '"  title="Edit">
            <i class="fa fa-edit text-body"></i>
        </button>

        <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                id="delete" route="' . route('company.services.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="Delete">
            <i class="fa fa-trash text-body"></i>
        </button>
    ';
            })

            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);

    }



    public function store(Request $request)

    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        CompanyService::create([

            'company_id'=>auth('company')->id(),
            'title'=>$request->title,
        ]);


        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);



    }




    public function fetch($id)
    {
        $service = CompanyService::find($id);

        if (!$service) {
            return response()->json(['message' => trans('messages.not-found')], 404);
        }

        return response()->json($service);
    }





    public function update(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',


        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $service = CompanyService::findOrFail($request->id);


        // Update the housekeeper details
        $service->update([
            'title' => $request->title,
        ]);


        return response()->json(['success' => true, 'message' => trans('messages.success-update')]);

    }




    public function destroy(Request $request)
    {
        $companyService = CompanyService::find($request->id);

        if ($companyService) {

            $companyService->delete();

            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
        } else {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        }
    }

}
