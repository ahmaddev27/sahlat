<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssuranceResources;
use App\Http\Resources\BannerResources;
use App\Http\Resources\CompanyResources;
use App\Http\Resources\HouseKeeperResources;
use App\Http\Resources\NotificationsResources;
use App\Models\Assurance;
use App\Models\Banner;
use App\Models\Company;
use App\Models\CompanyViewer;
use App\Models\Contact;
use App\Models\HouseKeeper;
use App\Models\HouseKeeperViewer;
use App\Models\Notification;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    use ApiResponseTrait;
    use ApiResponsePaginationTrait;


    public function companies(Request $request)
    {

        $query = Company::with('housekeepers');

        $perPage = $request->input('per_page', 5);

        $companies = $query->paginate($perPage);

        return $this->ApiResponsePaginationTrait(
            CompanyResources::collection($companies)
            , trans('messages.success'), true, 200);

    }


    public function company($id)
    {
        $company = Company::find($id);

        $userId = Auth::id();

        if (!$company) {
            return $this->apiRespose([], trans('messages.not_found'), false, 404);
        }

        $existingView = CompanyViewer::where('company_id', $company->id)
            ->where('user_id', $userId)
            ->exists();


        if (!$existingView) {
            CompanyViewer::create([
                'company_id' => $company->id,
                'user_id' => $userId,
            ]);
        }


        return $this->apiRespose(
            new CompanyResources($company),
            trans('messages.success'), true, 200);
    }


    public function housekeepersCompany(Request $request, $id)
    {

        $query = HouseKeeper::where('company_id', $id);
        $perPage = $request->input('per_page', 5);
        $housekeepers = $query->paginate($perPage);


        if ($housekeepers->isEmpty()) {
            return $this->apiRespose([], trans('messages.no t_found'), false, 404);
        }


        return $this->ApiResponsePaginationTrait(
            HouseKeeperResources::collection($housekeepers)
            , trans('messages.success'), true, 200);


    }


    public function housekeeper($id)
    {

        $houseKeeper = HouseKeeper::find($id);

        $userId = Auth::id();

        if (!$houseKeeper) {
            return $this->apiRespose([], trans('messages.not_found'), false, 404);
        }

        $existingView = HouseKeeperViewer::where('houseKeeper_id', $houseKeeper->id)
            ->where('user_id', $userId)
            ->exists();


        if (!$existingView) {
            HouseKeeperViewer::create([
                'houseKeeper_id' => $houseKeeper->id,
                'user_id' => $userId,
            ]);
        }

        return $this->apiRespose(new HouseKeeperResources($houseKeeper)
            , trans('messages.success'), true, 200);


    }


    public function banners()
    {
        return $this->apiRespose(BannerResources::collection(Banner::all())
            , trans('messages.success'), true, 200);

    }


    public function assurances()
    {
        return $this->apiRespose(
            AssuranceResources::collection(Assurance::where('status', 1)->get())
            , trans('messages.success'), true, 200);

    }

    public function settings()
    {

        $settings = Settings::all();
        $settingsArray = $settings->pluck('value', 'key')->toArray();

        if (isset($settingsArray['logo'])) {
            $settingsArray['logo'] = URL::to('/') . '/storage/' . $settingsArray['logo'];
        }

        if (isset($settingsArray['icon'])) {
            $settingsArray['icon'] = URL::to('/') . '/storage/' . $settingsArray['icon'];
        }
        return $this->apiRespose(
            $settingsArray
            , trans('messages.success'), true, 200);
    }


    public function contact(Request $request)
    {
        $rules = [
            'title' => 'required',
            'email' => 'required|email',
            'text' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

        try {
            Contact::create([
                'title' => $request->title,
                'email' => $request->email,
                'text' => $request->text,
                'user_id' => Auth::id(),
            ]);

            return $this->apiRespose([], trans('messages.success'), true, 200);
        } catch (\Exception $e) {
            return $this->apiRespose([], trans('messages.error_occurred'), false, 500);
        }
    }


    public function housekeepers(Request $request)
    {
        try {
            $query = Housekeeper::with('reviews')
                ->withAvg('reviews', 'value')
                ->where('status', 0)
                ->orderBy('reviews_avg_value', 'desc');

            if ($request->has('language')) {
                $languages = explode(',', $request->input('language'));
                $query->whereIn('language', $languages);
            }

            if ($request->has('religion')) {
                $religions = explode(',', $request->input('religion'));
                $query->whereIn('religion', $religions);
            }

            if ($request->has('nationality')) {
                $nationalities = explode(',', $request->input('nationality'));
                $query->whereIn('nationality', $nationalities);
            }

            if ($request->has('experience')) {
                $experience = $request->input('experience');
                $query->where('experience', $experience);
            }

            $perPage = $request->input('per_page', 10);
            $housekeepers = $query->paginate($perPage);

            if ($housekeepers->isEmpty()) {
                return $this->ApiResponsePaginationTrait(HouseKeeperResources::collection($housekeepers), trans('messages.not_found'), true, 200);
            }

            return $this->ApiResponsePaginationTrait(
                HouseKeeperResources::collection($housekeepers),
                trans('messages.success'), true, 200
            );
        } catch (\Exception $e) {
            return $this->apiRespose([], trans('messages.error_occurred'), false, 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('search');
            $results = [];

            if (!empty($query)) {
                $results = [
                    'Assurance' => AssuranceResources::collection(
                        Assurance::where('title', 'LIKE', "%$query%")->get()
                    ),
                    'HouseKeeper' => HouseKeeperResources::collection(
                        HouseKeeper::where('name', 'LIKE', "%$query%")->get()
                    ),
                    'Company' => CompanyResources::collection(
                        Company::where('name', 'LIKE', "%$query%")->get()
                    ),
                ];
            }

            return $this->apiRespose($results, trans('messages.success'), true, 200);
        } catch (\Exception $e) {
            return $this->apiRespose([], trans('messages.error_occurred'), false, 500);
        }
    }


    public function topRatedHousekeeper()
    {
        $query = Housekeeper::with('reviews')
            ->withAvg('reviews', 'value')
            ->where('status', 0)
            ->orderBy('reviews_avg_value', 'desc')
            ->take(5)->get();


        return $this->apiRespose(
            HouseKeeperResources::collection($query),
            trans('messages.success'),
            true,
            200
        );
    }


    public function mostOrderedAssurances()
    {
        $assurances = Assurance::withCount('AssuranceOrders')
            ->where('status', 1)
            ->orderBy('assurance_orders_count', 'desc')
            ->take(5)
            ->get();

        return $this->apiRespose(
            AssuranceResources::collection($assurances),
            trans('messages.success'),
            true,
            200
        );
    }


    public function notification(Request $request)
    {
        $query = Auth::user()->notifications()->latest();

        $perPage = $request->input('per_page', 10);

        $notifications = $query->paginate($perPage);

        $notifications->each(function ($notification) {
            $notification->update(['status' => 1]);
        });

        return $this->ApiResponsePaginationTrait(
            NotificationsResources::collection($notifications),
            trans('messages.success'),
            true,
            200
        );
    }


}
