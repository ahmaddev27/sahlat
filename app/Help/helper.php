<?php


use App\Models\HouseKeeperOrder;
use Carbon\Carbon;

function companies()
{
    return \App\Models\Company::count();

}

function HouseKeepersStatus($status)
{
    if ($status == 0) {
        return trans('main.notEmployed');

    } elseif ($status == 1) {
        return trans('main.employed');

    }

}


function UserStatus($status)
{
    if ($status == 0) {
        return trans('main.NotCompleted');

    } elseif ($status == 1) {
        return trans('main.Completed');

    }

}


function ContactStatus($status)
{
    if ($status == 1) {
        return trans('contacts.read');

    } elseif ($status == 0) {
        return trans('contacts.not-read');

    }

}


function formatedPhone($phone)
{
    // Remove all non-numeric characters from the phone number
    $formatedphone = preg_replace('/\D/', '', $phone);

    // Check if the formatted phone number has 9 digits and starts with 971 (for UAE)
    if (strlen($formatedphone) === 9) {
        // Format the phone number as +971 57 854 1298
        return '+971 ' . substr($phone, 0, 2) . ' ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 4);
    }

    // Return the phone number unformatted if it's not valid (i.e., not 9 digits)
    return $formatedphone;

}


function NewContacts()
{
    return \App\Models\Contact::where('status', 0)->count();

}


function allContacts()
{
    return \App\Models\Contact::count();

}

function gender($x)
{
    if ($x == 0) {
        return trans('user.female');

    } elseif ($x == 1) {
        return trans('user.male');

    }

}

function AssuranceStatus($status)
{
    if ($status == 0) {
        return trans('main.active');

    } elseif ($status == 1) {
        return trans('main.not-active');

    }

}


function paymentStatus($status)
{
    if ($status == 0) {
        return trans('main.not-payed');

    } elseif ($status == 1) {
        return trans('main.payed');

    }

}


function StatusesViolations($id = null)
{
    $status = [
        '0' => trans('main.new_'),
        '1' => trans('main.under_payed'),
        '2' => trans('main.payed'),
        '3' => trans('main.completed'),
        '4' => trans('main.closed')
    ];


    if ($id !== null) {
        return $status[$id] ?? null;
    }

    return $status;


}

function StatusesAssurance($id = null)
{
    $status = [
        '0' => trans('main.new_'),
        '1' => trans('main.negotiation'),
        '2' => trans('main.under_payed'),
        '3' => trans('main.payed'),
        '4' => trans('main.completed'),
        '5' => trans('main.closed')
    ];


    if ($id !== null) {
        return $status[$id] ?? null;
    }

    return $status;


}

function HouseKeeperStatuses($id = null)
{
    $status = [
        '0' => trans('main.new_'),
        '1' => trans('main.negotiation'),
        '2' => trans('main.under_payed'),
        '3' => trans('main.active_contract'),
        '4' => trans('main.complete_contract'),
        '5' => trans('main.closed')
    ];


    if ($id !== null) {
        return $status[$id] ?? null;
    }

    return $status;


}
function HouseKeeperHourlyStatuses($id = null)
{
    $status = [
        '0' => trans('main.new_'),
        '1' => trans('main.completed'),
        '3' => trans('main.closed')
    ];


    if ($id !== null) {
        return $status[$id] ?? null;
    }

    return $status;


}

function user_statuts($id)
{
    $status = [
        '0' => trans('main.not-active'),
        '1' => trans('main.active'),
    ];


    return $status[$id] ?? null;


}


function HouseKeeperOrdersByStatus($status)
{
    return \App\Models\HouseKeeperOrder::where('status', $status)->count();
}



function orderStatus($id = null)
{
    $status = [
        '0' => trans('main.new_'),
        '1' => trans('main.negotiation'),
        '2' => trans('main.under_payed'),
        '3' => trans('main.payed'),
        '4 ' => trans('main.implemented'),
        '5' => trans('main.closed')
    ];


    if ($id !== null) {
        return $status[$id] ?? null;
    }

    return $status;


}

function houseKeeperExpiringOrdersCounts()
{
    $oneMonthAgo = Carbon::now()->subMonth();
    return HouseKeeperOrder::where('status', 3)->where('sing_date', '<', $oneMonthAgo)->count();
}

function houseKeeperExpiringOrdersCountsCompany()
{
    $oneMonthAgo = Carbon::now()->subMonth();
    return HouseKeeperOrder::where('status', 3)
        ->whereHas('housekeeper', function ($query) {
            $query->where('company_id', auth()->id());
        })
        ->where('sing_date', '<', $oneMonthAgo)
        ->count();
}

function OrdorClass($status)
{

    if ($status == 0) {
        return 'badge-warning';
    } elseif ($status == 1) {
        return trans('badge-success');
    } elseif ($status == 2) {
        return trans('badge-info');
    } elseif ($status == 3) {
        return trans('badge-primary');
    } elseif ($status == 4) {
        return trans('badge-secondary');

    } elseif ($status == 5) {
        return trans('badge-dark');
    }

}


function ContactClass($status)
{

    if ($status == 0) {
        return 'badge-info';
    } elseif ($status == 1) {
        return trans('badge-success');

    }
}


function setting($settingKey, $locale = null)
{

    $setting = \App\Models\Settings::where('key', $settingKey)->first();

    if ($setting) {
        return $setting->value;
    } else {
        return 'Setting not found';
    }
}


function Assurance()
{
    return \App\Models\Assurance::count();
}


function AssuranceOrders()
{
    return \App\Models\AssuranceOrder::count();
}


function users()
{
    return \App\Models\AppUser::count();
}

function AssuranceOrdersByStatus($status)
{
    return \App\Models\AssuranceOrder::where('status', $status)->count();
}


function AssuranceOrdersNew()
{
    return \App\Models\AssuranceOrder::where('status', 0)->count();
}

function AssuranceOrdersDone()
{
    return \App\Models\AssuranceOrder::where('status', 4)->count();
}


function getDoneOrdersPercentage()
{
    $totalOrders = \App\Models\AssuranceOrder::count();
    $doneOrders = \App\Models\AssuranceOrder::where('status', 4)->count();

    if ($totalOrders === 0) {
        return 0; // Avoid division by zero
    }

    return round(($doneOrders / $totalOrders) * 100, 1);
}


function HouseKeepers()
{
    return \App\Models\HouseKeeper::count();
}


function HouseKeepersOrders()
{
    return \App\Models\HouseKeeperOrder::count();
}

function HouseKeepersHourlyOrders()
{
    return \App\Models\HouseKeeperHourlyOrder::count();
}


function HouseKeeperOrdersPendding()
{
    return \App\Models\HouseKeeperOrder::where('status', 0)->count();
}

function HouseKeeperHourlyOrdersPendding()
{
    return \App\Models\HouseKeeperHourlyOrder::where('status', 0)->count();
}

function HouseKeeperHourlyOrdersDone()
{
    return \App\Models\HouseKeeperHourlyOrder::where('status', 1)->count();
}

function HouseKeeperOrdersDone()
{
    return \App\Models\HouseKeeperOrder::where('status', 4)->count();
}

function violationsPendding()
{
    return \App\Models\Violation::where('status', 0)->count();
}

function violationsByStatus($status)
{
    return \App\Models\Violation::where('status', $status)->count();
}

function violationsDone()
{
    return \App\Models\Violation::where('status', 3)->count();
}

function total_payments()
{
    return \App\Models\Payment::sum('value');
}


function payment_assurances()
{
    $totalPayments = \App\Models\Payment::count();
    if ($totalPayments === 0) {
        return 0;
    }
    $assuredPayments = \App\Models\Payment::whereNotNull('assurance_order_id')->count();
    return round(($assuredPayments / $totalPayments) * 100);

}


function payment_housekeeper()
{
    $totalPayments = \App\Models\Payment::count();
    if ($totalPayments === 0) {
        return 0;
    }
    $assuredPayments = \App\Models\Payment::whereNotNull('house_keeper_order_id')->count();
    return round(($assuredPayments / $totalPayments) * 100);

}

function payment_housekeeper_hourly()
{
    $totalPayments = \App\Models\Payment::count();
    if ($totalPayments === 0) {
        return 0;
    }
    $assuredPayments = \App\Models\Payment::whereNotNull('house_keeper_hourly_order_id')->count();
    return round(($assuredPayments / $totalPayments) * 100);

}

function payment_violations()
{
    $totalPayments = \App\Models\Payment::count();
    if ($totalPayments === 0) {
        return 0;
    }
    $assuredPayments = \App\Models\Payment::whereNotNull('violation_id')->count();
    return round(($assuredPayments / $totalPayments) * 100);

}


function paymentsPercentage()
{
    // Count orders with associated payments
    $violationsCount = \App\Models\Violation::whereHas('payment')->count(); // Violations with payments
    $houseKeepersOrdersCount = \App\Models\HouseKeeperOrder::whereHas('payment')->count(); // Housekeeper orders with payments
    $houseKeepersHourlyOrdersCount = \App\Models\HouseKeeperHourlyOrder::whereHas('payment')->count(); // Hourly housekeeper orders with payments
    $assuranceOrdersCount = \App\Models\AssuranceOrder::whereHas('payment')->count(); // Assurance orders with payments

    // Total orders with payments
    $totalOrdersWithPayments = $violationsCount + $houseKeepersOrdersCount + $houseKeepersHourlyOrdersCount + $assuranceOrdersCount;

    if ($totalOrdersWithPayments === 0) {
        return 0; // Avoid division by zero
    }

    // Total payments count
    $paymentsCount = \App\Models\Payment::count();

    // Calculate the percentage
    return round(($paymentsCount / $totalOrdersWithPayments) * 100);
}


function getDoneviolationsPercentage()
{
    $totalOrders = \App\Models\Violation::count();
    $doneOrders = \App\Models\Violation::where('status', 3)->count();

    if ($totalOrders === 0) {
        return 0; // Avoid division by zero
    }

    return round(($doneOrders / $totalOrders) * 100, 1);
}


function getDoneHouseKeeperOrdersPercentage()
{
    $totalOrders = \App\Models\HouseKeeperOrder::count();
    $doneOrders = \App\Models\HouseKeeperOrder::where('status', 3)->count();

    if ($totalOrders === 0) {
        return 0; // Avoid division by zero
    }

    return round(($doneOrders / $totalOrders) * 100, 1);
}


function getDoneHouseKeeperHourlyOrdersPercentage()
{
    $totalOrders = \App\Models\HouseKeeperHourlyOrder::count();
    $doneOrders = \App\Models\HouseKeeperHourlyOrder::where('status', 1)->count();

    if ($totalOrders === 0) {
        return 0; // Avoid division by zero
    }

    return round(($doneOrders / $totalOrders) * 100, 1);
}


function getAllReligions($id = null)
{
    $religions = [
        '1' => trans('housekeeper.islam'),
        '2' => trans('housekeeper.christianity'),
        '3' => trans('housekeeper.judaism'),
        '4' => trans('housekeeper.hinduism'),
        '5' => trans('housekeeper.buddhism'),
        // '6' => trans('housekeeper.sikhism'),
        // '7' =>trans('housekeeper.atheism'),
        '8' => trans('housekeeper.other'),
    ];

    if ($id !== null) {
        return $religions[$id] ?? null;
    }
    return $religions;

}


function getAllLangs($id = null)
{
    $langs = [
        '1' => trans('housekeeper.ar'),
        '2' => trans('housekeeper.en'),
    ];

    if ($id !== null) {
        return $langs[$id] ?? null;
    }
    return $langs;
}

function Nationalities($id = null)
{
    $nationalities = [
        '1' => __('housekeeper.india'),
        // '2' => __('housekeeper.pakistan'),
        '2' => __('housekeeper.bangladesh'),
        '3' => __('housekeeper.philippines'),
//        '3' => __('housekeeper.egypt'),
        '4' => __('housekeeper.sri_lanka'),
        '5' => __('housekeeper.nepal'),
        '6' => __('housekeeper.uganda'),
        // '5' => __('housekeeper.syria'),
//        '7' => __('housekeeper.sudan'),
        // '11' => __('housekeeper.kenya'),
        // '7' => __('housekeeper.tunisia'),
        // '13' => __('housekeeper.morocco'),
//        '6' => __('housekeeper.yemen'),
        // '15' => __('housekeeper.africa'),
        // '16' => __('housekeeper.afghanistan'),
        // '17' => __('housekeeper.algeria'),
        // '9' => __('housekeeper.iraq'),
        // '19' => __('housekeeper.uzbekistan'),
        '7' => __('housekeeper.indonesia'),
        // '21' => __('housekeeper.kenya'),
        // '22' => __('housekeeper.nigeria'),
        '8' => __('housekeeper.ethiopia'),
        '9' => trans('housekeeper.other'),
        // '24' => __('housekeeper.tanzania'),
        // '25' => __('housekeeper.malaysia'),
        // '26' => __('housekeeper.romania'),
        // '27' => __('housekeeper.poland'),
        // '28' => __('housekeeper.somalia'),
        // '29' => __('housekeeper.kyrgyzstan'),
        // '30' => __('housekeeper.myanmar'),
    ];

    if ($id !== null) {
        return $nationalities[$id] ?? null;
    }

    return $nationalities;
}


function cities($id = null)
{

    $cites =
        [
            '1' => trans('main.Dubai'),
            '2' => trans('main.Abu Dhabi'),
            '3' => trans('main.Sharjah'),
            '4' => trans('main.Ras Al Khaimah'),
            '5' => trans('main.Al Ain'),
            '6' => trans('main.Ajman'),
            '7' => trans('main.Fujairah'),
        ];

    if ($id !== null) {
        return $cites[$id] ?? null;
    }

    return $cites;
}

function formatPagination($paginator)
{
    return [
        'current_page' => $paginator->currentPage(),
        'last_page' => $paginator->lastPage(),
        'per_page' => $paginator->perPage(),
        'total' => $paginator->total(),
    ];
}

?>

