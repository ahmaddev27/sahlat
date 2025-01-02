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
    protected function handleStatusUpdates($order, $request){
        $user = $order->user;
        $link = route('api.violationsRecords', $order->id); // Modify based on your actual route

        // Store the current locale
        $currentLocale = app()->getLocale();

        // Set the locale to the user's language
        app()->setLocale($user->lang);

        switch ($order->status) {

            case 2:

                $remaining_amount = 0;


                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->price,
                ]);


                $message = 'Your payment for order has been processed successfully';
                $image = asset('icons/payment.png');
                $this->createNotification($user, 'Payment Processed', $message, $link, $order->id, $image);
                break;


            case 3:

                $remaining_amount = 0;
                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->price,
                ]);


                $message = 'Your Order has been Completed successfully';
                $image = asset('icons/complete.png');
                $this->createNotification($user, 'Order Completed', $message, $link, $order->id, $image);
                break;


            case 4:
                $order->note = $request->note;
                $image = asset('icons/cancel.png');
                $message = 'Your Order has been Closed';
                $this->createNotification($user, 'Order Closed', $message, $link, $order->id, $image);
                break;
        }

        // Revert the locale back to the original
        app()->setLocale($currentLocale);
    }

    protected function createNotification($user, $title, $body, $link, $orderId, $image)
    {
        $type = 'assurance';

        $user->notify(new OrderNotification(trans('notifications.' . $title), trans('notifications.' . $body), $link, $orderId, $type, $image));

        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $body,
            'link' => $link,
            'assurance_order' => $orderId,
            'type' => $type,
            'image' => $image,
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

            case 2:

                $remaining_amount = 0;

                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $message = 'Your payment for order has been processed successfully';
                $image = asset('icons/payment.png');
                $this->ViolationcreateNotification($user, 'Payment Processed', $message, $link, $order->id, $image);
                break;


            case 3:

                $remaining_amount = 0;
                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $message = 'Your Order has been Completed successfully';
                $image = asset('icons/complete.png');
                $this->ViolationcreateNotification($user, 'Order Completed', $message, $link, $order->id, $image);
                break;


            case 4:
                $order->note = $request->note;
                $image = asset('icons/cancel.png');
                $message = 'Your Order has been Closed';
                $this->ViolationcreateNotification($user, 'Order Closed', $message, $link, $order->id, $image);
                break;
        }

        // Revert the locale back to the original
        app()->setLocale($currentLocale);
    }

    protected function ViolationcreateNotification($user, $title, $message, $link, $orderId, $image)
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
            'image' => $image

        ]);

        $user->notify(new OrderNotification(trans('notifications.' . $title), trans('notifications.' . $message), $link, $orderId, $type, $image));
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

            case 2:

                $remaining_amount = 0;

                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $message = 'Your payment for order has been processed successfully';
                $image = asset('icons/payment.png');
                $this->HouseKeepercreateNotification($user, 'Payment Processed', $message, $link, $order->id, $image);
                break;


            case 3:

                $remaining_amount = 0;
                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $message = 'Your Order has been Completed successfully';
                $image = asset('icons/complete.png');
                $this->HouseKeepercreateNotification($user, 'Order Completed', $message, $link, $order->id, $image);
                break;



            case 4:
                $message = 'Your order has been closed';
                $image = asset('icons/cancel.png');
                $this->HouseKeepercreateNotification($user, 'Order Closed', $message, $link, $order->id, $image);
                break;
        }

        // Revert the locale back to the original
        app()->setLocale($currentLocale);
    }

    protected function HouseKeepercreateNotification($user, $title, $message, $link, $orderId, $image)
    {
        $type = 'houseKeeper';
        // Send notification to the user
        $user->notify(new OrderNotification(trans('notifications.' . $title), trans('notifications.' . $message), $link, $orderId, $type, $image));
        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'houseKeeper_order' => $orderId,
            'status' => 0,
            'type' => $type,
            'image' => $image,

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

            case 2:

                $remaining_amount = 0;

                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $message = 'Your payment for order has been processed successfully';
                $image = asset('icons/payment.png');
                $this->HourlycreateNotification($user, 'Payment Processed', $message, $order->id, $image);
                break;


            case 3:
                $order->house_keeper_id = $request->housekeeper_id;
                $remaining_amount = 0;
                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $message = 'Your Order has been Completed successfully';
                $image = asset('icons/complete.png');
                $this->HourlycreateNotification($user, 'Order Completed', $message, $order->id, $image);
                break;


            case 4:
                $message = 'Your order has been closed';
                $image = asset('icons/cancel.png');
                $this->HourlycreateNotification($user, 'Order Closed', $message, $order->id, $image);
                break;

//            case 1:
//                // Assign housekeeper and notify payment required
//                $order->house_keeper_id = $request->housekeeper_id;
//                $title = 'Payment Required';
//                $body = "Please pay for your order {$order->n_id}";
//                $this->HourlycreateNotification($user, $title, $body, $order->id);
//                break;
//
//            case 2:
//                // Process payment and notify the user
//                $this->processHourlyOrderPayment($order);
//                $message = "Your payment for order {$order->n_id} has been processed successfully.";
//                $this->HourlycreateNotification($user, 'Payment Processed', $message, $order->id);
//                break;
//
//            case 3:
//                // Mark housekeeper as available
//                $order->housekeeper->update(['status' => 1]);
//                $message = "Housekeeper for order {$order->n_id} is now ready ";
//                $this->HourlycreateNotification($user, 'Housekeeper Ready', $message, $order->id);
//                break;


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
    protected function HourlycreateNotification($user, $title, $message, $orderId, $image)
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
            'image' => $image

        ]);

        // Notify the user
        $user->notify(new OrderNotification(trans('notifications.' . $title), trans('notifications.' . $message), $link, $orderId, $type, $image));
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
