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

    protected function handleStatus($order, $type)
    {


        $user = $order->user;
        $link = route("api.{$type}Records", $order->id);

        // Store the current locale
        $currentLocale = app()->getLocale();

        // Set the locale to the user's language
        app()->setLocale($user->lang);

        // Define status messages and icons
        $statusMessages = [
            1 => [
                'title' => 'Payment Processed',
                'message' => 'Your payment for the order has been processed successfully',
                'icon' => asset('icons/payment.png'),
            ],
            2 => [
                'title' => 'Payment Processed',
                'message' => 'Your payment for the order has been processed successfully',
                'icon' => asset('icons/payment.png'),
            ],
            3 => [
                'title' => 'Order Completed',
                'message' => 'Your order has been completed successfully',
                'icon' => asset('icons/complete.png'),
            ],
            4 => [
                'title' => 'Order Closed',
                'message' => 'Your order has been closed',
                'icon' => asset('icons/cancel.png'),
            ],
        ];

        if (isset($statusMessages[$order->status])) {
            // Update payment details for statuses that require full payment
            if (in_array($order->status, [2, 3])) {
                $remaining_amount = 0;
                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2, // Marked as completely paid
                    'payment_value' => $order->value,
                ]);
            }

            // Send notification
            $this->createNotification(
                $user,
                $statusMessages[$order->status]['title'],
                $statusMessages[$order->status]['message'],
                $link,
                $order->id,
                $type,
                $statusMessages[$order->status]['icon']
            );
        }

        // Revert the locale back to the original
        app()->setLocale($currentLocale);
    }


    protected function createNotification($user, $title, $body, $link, $orderId, $type, $image)
    {
        // Send real-time notification via the notify method
        $user->notify(new OrderNotification(
            trans('notifications.' . $title),
            trans('notifications.' . $body),
            $link,
            $orderId,
            $type,
            $image
        ));

        // Store the notification in the database
        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $body,
            'link' => $link,
            'order_id' => $orderId, // Ensure the correct reference for the order ID
            'type' => $type, // Notification type// Store the type of the order (such as "order", "violation", etc.)
            'image' => $image,
            'status' => 0, // Pending status for notification
        ]);
    }



    protected function AssurancehandleStatusUpdates($order, $request){
        $user = $order->user;
        $link = route('api.assuranceRecords', $order->id); // Modify based on your actual route

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
                $this->createNotification($user, 'Payment Processed', $message, $link, $order->id, 'assurance',$image);
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
                $this->createNotification($user, 'Order Completed', $message, $link, $order->id,'assurance', $image);
                break;


            case 4:
                $order->note = $request->note;
                $image = asset('icons/cancel.png');
                $message = 'Your Order has been Closed';

                $this->createNotification($user, 'Order Closed', $message, $link, $order->id,'assurance', $image);
                break;
        }

        // Revert the locale back to the original
        app()->setLocale($currentLocale);
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
        $link = route('api.violationRecords', $order->id); // Modify based on your actual route

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
                $this->createNotification($user, 'Payment Processed', $message, $link, $order->id, 'violation',$image);
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
                $this->createNotification($user, 'Order Complete', $message, $link, $order->id, 'violation',$image);

                break;


            case 4:
                $order->note = $request->note;
                $image = asset('icons/cancel.png');
                $message = 'Your Order has been Closed';
                $this->createNotification($user, 'Order Closed', $message, $link, $order->id,'violation', $image);
                break;
        }

        // Revert the locale back to the original
        app()->setLocale($currentLocale);
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
        $link = route('api.housekeeperRecords', $order->id);

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
                $this->createNotification($user, 'Payment Processed', $message, $link, $order->id, 'housekeeper',$image);
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
                $this->createNotification($user, 'Order Completed', $message, $link, $order->id,'housekeeper',$image);
                break;



            case 4:
                $message = 'Your order has been closed';
                $image = asset('icons/cancel.png');
                $this->createNotification($user, 'Order Closed', $message, $link, $order->id, 'housekeeper',$image);
                break;
        }

        // Revert the locale back to the original
        app()->setLocale($currentLocale);
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
        $link = route('api.housekeeper_hourly_orderRecords', $order->id); // Modify based on your actual route

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
                $this->createNotification($user, 'Payment Processed', $message, $link,$order->id, 'housekeeper_hourly_order',$image);

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
                $this->createNotification($user, 'Order Completed', $message, $link,$order->id, 'housekeeper_hourly_order',$image);

                break;


            case 4:
                $message = 'Your order has been closed';
                $image = asset('icons/cancel.png');
                $this->createNotification($user, 'Order Closed', $message, $link,$order->id, 'housekeeper_hourly_order',$image);
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




}
