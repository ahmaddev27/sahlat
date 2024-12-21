<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\OrderAttachment;
use App\Models\Payment;
use App\Notifications\OrderNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

//ForAssurances
    protected function handleStatusUpdates($order, $request)
{
    $link = route('api.assurancesRecords', $order->id);
    $user = $order->user;

    // Store the current locale
    $currentLocale = app()->getLocale();

    // Set the locale to the user's language
    app()->setLocale($user->lang);

    switch ($order->status) {
        case 1:
            $body = trans('notifications.Please come to sign the contract for order :order_id', ['order_id' => $order->n_id]);
            $this->createNotification($user, trans('notifications.Negotiation Required for assurance order'), $body, $link, $order->id);
            break;

        case 2:
            $amount = (double)$order->value + (double)setting('commission');
            $body = trans('notifications.Please pay :amount ADE for your order :order_id', ['amount' => $amount, 'order_id' => $order->n_id]);
            $this->createNotification($user, trans('notifications.Payment Required'), $body, $link, $order->id);
            break;

        case 3:
            $this->processPayment($order);
            $body = trans('notifications.Your payment for order :order_id has been processed successfully.', ['order_id' => $order->n_id]);
            $this->createNotification($user, trans('notifications.Payment Processed'), $body, $link, $order->id);
            break;

        case 4:
            $body = trans('notifications.Your Order :order_id has been Completed successfully.', ['order_id' => $order->n_id]);
            $this->createNotification($user, trans('notifications.Order Completed'), $body, $link, $order->id);
            break;

        case 5:
            $order->note = $request->note;
            $body = trans('notifications.Your Order :order_id has been Closed', ['order_id' => $order->n_id]);
            $this->createNotification($user, trans('notifications.Order Closed'), $body, $link, $order->id);
            break;
    }

    // Revert the locale back to the original
    app()->setLocale($currentLocale);
}
    protected function createNotification($user, $title, $body, $link, $orderId)
    {
        $type = 'assurance';

        $user->notify(new OrderNotification($title, $body, $link, $orderId, $type, url('storage/') . setting('logo')));

        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $body,
            'link' => $link,
            'assurance_order' => $orderId,
            'type' => $type,
            'image' => setting('icon'),
            'status' => 0,
        ]);

    }

    protected function processPayment($order)
    {
        Payment::create([
            'admin_id' => auth()->id(),
            'user_id' => $order->user_id,
            'value' => $order->assurance->price + (int)setting('commission'),
            'status' => 1,
            'type' => 'dashboard',
            'assurance_order_id' => $order->id,
        ]);
    }

    protected function handleFileUpload($request, $order)
    {
        $file = $request->file('attachment');
        $filePath = $file->store('OrdersAttachments', 'public');

        OrderAttachment::create([
            'assurance_order_id' => $order->id,
            'file' => $filePath,
            'type' => $order->status == 2 ? 'contract' : 'Payment received',
        ]);
    }


//ForValidations
    protected function ViolationhandleStatusUpdates($order, $request)
{
    $user = $order->user;
    $link = route('api.violationsRecords', $order->id); // Modify based on your actual route

    // Store the current locale
    $currentLocale = app()->getLocale();

    // Set the locale to the user's language
    app()->setLocale($user->lang);

    switch ($order->status) {
        case 1:
            // Handle status 1: Payment required
            $order->value = $request->value; // Set payment value
            $amount = (double)$order->value + (double)setting('commission');
            $body = trans('notifications.Please pay :amount ADE for your order :order_id', ['amount' => $amount, 'order_id' => $order->n_id]);
            $this->ViolationcreateNotification($user, trans('notifications.Payment Required'), $body, $link, $order->id);
            break;

        case 2:
            // Handle status 2: Payment processed
            Payment::create([
                'admin_id' => auth()->id(),
                'user_id' => $order->user_id,
                'value' => $order->value + (int)setting('commission'),
                'status' => 1,
                'type' => 'dashboard',
                'violation_id' => $order->id,
            ]);

            $message = trans('notifications.Your payment for order :order_id has been processed successfully.', ['order_id' => $order->n_id]);
            $this->ViolationcreateNotification($user, trans('notifications.Payment Processed'), $message, $link, $order->id);
            break;

        case 3:
            $message = trans('notifications.Your Order :order_id has been Completed successfully.', ['order_id' => $order->n_id]);
            $this->ViolationcreateNotification($user, trans('notifications.Order Completed'), $message, $link, $order->id);
            break;

        case 4:
            $order->note = $request->note;
            $message = trans('notifications.Your Order :order_id has been Closed', ['order_id' => $order->n_id]);
            $this->ViolationcreateNotification($user, trans('notifications.Order Closed'), $message, $link, $order->id);
            break;
    }

    // Revert the locale back to the original
    app()->setLocale($currentLocale);
}
    protected function ViolationcreateNotification($user, $title, $message, $link, $orderId)
    {

        $type = 'violation';
        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'violation_order' => $orderId,
            'type' => $type,
            'status' => 0,
            'image' => setting('icon'),

        ]);

        $user->notify(new OrderNotification($title, $message, $link, $orderId, $type, url('storage/') . setting('logo')));
    }

    protected function ViolationhandleFileUpload($request, $order)
    {
        $file = $request->file('attachment');
        $filePath = $file->store('OrdersAttachments', 'public');

        OrderAttachment::create([
            'violation_id' => $order->id,
            'file' => $filePath,
            'type' => 'Payment received',
        ]);
    }


    protected function HouseKeeperhandleStatusUpdates($order, $request)
{
    $user = $order->user;
    $link = route('api.houseKeeperRecords', $order->id);

    // Store the current locale
    $currentLocale = app()->getLocale();

    // Set the locale to the user's language
    app()->setLocale($user->lang);

    switch ($order->status) {
        case 1:
            $body = trans('notifications.Please come to sign the contract for order :order_id', ['order_id' => $order->n_id]);
            $this->HouseKeepercreateNotification($user, trans('notifications.Negotiation Required'), $body, $link, $order->id);
            break;

        case 2:
            $message = trans('notifications.Payment required for order :order_id', ['order_id' => $order->n_id]);
            $this->HouseKeepercreateNotification($user, trans('notifications.Payment Required'), $message, $link, $order->id);
            break;

        case 3:
            $this->HouseKeeperprocessPayment($order);
            $order->housekeeper->update(['status' => 1]);
            $order->update(['sing_date' => Carbon::now()]);
            $message = trans('notifications.Housekeeper for order :order_id is now ready', ['order_id' => $order->n_id]);
            $this->HouseKeepercreateNotification($user, trans('notifications.Housekeeper Ready'), $message, $link, $order->id);
            break;

        case 4:
            $order->housekeeper->update(['status' => 0]);
            $order->note = $request->note;
            $message = trans('notifications.Your order :order_id is now completed', ['order_id' => $order->n_id]);
            $this->HouseKeepercreateNotification($user, trans('notifications.Order Completed'), $message, $link, $order->id);
            break;

        case 5:
            $order->housekeeper->update(['status' => 0]);
            $order->note = $request->note;
            $message = trans('notifications.Your order :order_id has been closed', ['order_id' => $order->n_id]);
            $this->HouseKeepercreateNotification($user, trans('notifications.Order Closed'), $message, $link, $order->id);
            break;
    }

    // Revert the locale back to the original
    app()->setLocale($currentLocale);
}

    protected function HouseKeepercreateNotification($user, $title, $message, $link, $orderId)
    {
        $type = 'houseKeeper';
        // Send notification to the user
        $user->notify(new OrderNotification($title, $message, $link, $orderId, $type, url('storage/') . setting('logo')));
        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'houseKeeper_order' => $orderId,
            'status' => 0,
            'type' => $type,
            'image' => setting('icon'),

        ]);


    }

    protected function HouseKeeperprocessPayment($order)
    {
        Payment::create([
            'admin_id' => auth()->id(),
            'user_id' => $order->user_id,
            'value' => $order->housekeeper->salary + (int)setting('commission'),
            'status' => 1,
            'type' => 'dashboard',
            'house_keeper_order_id' => $order->id,
        ]);
    }

    protected function HouseKeeperhandleFileUpload($request, $order, $file_type, $type)
    {
        // Get the file from the request
        $file = $request->file($file_type);

        // Store the file in a specific directory under 'public'
        $filePath = $file->store('OrdersAttachments', 'public');

        // Save the file record in the database with its type (payment or contract)
        OrderAttachment::create([
            'house_keeper_order_id' => $order->id,
            'file' => $filePath,
            'type' => $type, // 'Payment received' or 'contract'
        ]);
    }


    // Handle logic for each order status
    protected function handleHourlyOrderStatus($order, $request)
    {
        $user = $order->user;

        switch ($order->status) {
            case 1:
                // Assign housekeeper and notify payment required
                $order->house_keeper_id = $request->housekeeper_id;
                $title = 'Payment Required';
                $body = "Please pay for your order {$order->n_id}";
                $this->HourlycreateNotification($user, $title, $body, $order->id);
                break;

            case 2:
                // Process payment and notify the user
                $this->processHourlyOrderPayment($order);
                $message = "Your payment for order {$order->n_id} has been processed successfully.";
                $this->HourlycreateNotification($user, 'Payment Processed', $message, $order->id);
                break;

            case 3:
                // Mark housekeeper as available
                $order->housekeeper->update(['status' => 1]);
                $message = "Housekeeper for order {$order->n_id} is now ready ";
                $this->HourlycreateNotification($user, 'Housekeeper Ready', $message, $order->id);
                break;

            case 4:

                $order->housekeeper->update(['status' => 0]);
                $message = "your  order {$order->n_id} is now Completed.";
                $this->HourlycreateNotification($user, 'Order Completed', $message, $order->id);
                break;

            case 5:
                // Add a note to the order
                $order->housekeeper->update(['status' => 0]);
                $order->note = $request->note;
                $message = "Order has been closed {$order->n_id}: {$request->note}";
                $this->HourlycreateNotification($user, 'Order Closed', $message, $order->id);
                break;

            default:
                \Log::warning('Unknown status in handleHourlyOrderStatus: ' . $order->status);
                break;
        }
    }

// Handle file upload
    protected function handleHourlyOrderFileUpload($request, $order)
    {
        $file = $request->file('attachment');
        $filePath = $file->store('OrdersAttachments', 'public');
        OrderAttachment::create([
            'house_keeper_hourly_order_id' => $order->id,
            'file' => $filePath,
            'type' => 'file',
        ]);
    }

// Create a notification
    protected function HourlycreateNotification($user, $title, $message, $orderId)
    {

        $type = 'houseKeeperHourly';
        $link = route('api.getHourlyHouseKeeperRecords', $orderId);
        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'houseKeeperHourly_order' => $orderId,
            'type' => $type,
            'status' => 0,
            'image' => setting('icon'),

        ]);

        // Notify the user
        $user->notify(new OrderNotification($title, $message, $link, $orderId, $type, url('storage/') . setting('logo')));
    }

// Process payment for the order
    protected function processHourlyOrderPayment($order)
    {
        Payment::create([
            'admin_id' => auth()->id(),
            'user_id' => $order->user_id,
            'value' => ($order->company->hourly_price * $order->hours) + (int)setting('commission'),
            'status' => 1,
            'type' => 'dashboard',
            'house_keeper_hourly_order_id' => $order->id,
        ]);
    }


}
