<?php

namespace App\Http\Resources;

use App\Models\HouseKeeperOrder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class HouseKeeperResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userId = Auth::id();

        $exsites = HouseKeeperOrder::where('housekeeper_id', $this->id)
            ->where('user_id', $userId)
            ->whereIn('status', [0, 1, 2])
            ->first();

        $running_order = HouseKeeperOrder::where('housekeeper_id', $this->id)
            ->where('user_id', $userId)
            ->where('status', 3)
            ->first();

        $can_order = ($exsites && $running_order) || (!$exsites && !$running_order) ? 1 : 0;
        $can_cancel = $exsites && !$running_order ? 1 : 0;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'status' => HouseKeepersStatus($this->status),
            'salary' => $this->salary,
            'hours_working' => 300,
            'experience' => $this->experience,
            'description' => $this->description,
            'company_id' => $this->company_id,
            'company_name' => $this->company->name ?? null,
            'company_logo' => $this->company ? $this->company->getAvatar() : null,
            'company_location' => $this->company ? cities($this->company->address) : null,
            'nationality' => Nationalities($this->nationality),
            'language' => getAllLangs($this->language),
            'religion' => getAllReligions($this->religion),
            'avatar' => $this->getAvatar(),
            'views' => $this->views->count(),
            'review' => $this->averageReview(),
            'reviews_count' => $this->reviews->count(),
            'can_order' => $can_order,
            'can_cancel' => $can_cancel,
            'order_id' => $exsites->id ?? null,
            'order_status' => $exsites || $running_order ? [
                'status' => $exsites ? HouseKeeperStatuses((int)$exsites->status) : HouseKeeperStatuses((int)$running_order->status),
                'id' => $exsites ? (int)$exsites->status : $running_order->status
            ] : null,
        ];

    }
    }
