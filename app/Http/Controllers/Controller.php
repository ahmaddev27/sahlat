<?php

namespace App\Http\Controllers;

use App\Models\DashboardPayment;
use App\Models\Notification;
use App\Models\OrderAttachment;
use App\Models\Payment;
use App\Notifications\OrderNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function handleStatus($order, $type)
    {


        $user = $order->user;
        $link = route("api.{$type}Records", $order->id);


        // Define status messages and icons
        $statusMessages = [
            1 => [
                'key_title' => 'payment_processed_title',
                'key_message' => 'payment_processed_message',
                'icon' => asset('icons/payment.png'),
            ],
            2 => [
                'key_title' => 'payment_processed_title',
                'key_message' => 'payment_processed_message',
                'icon' => asset('icons/payment.png'),
            ],
            3 => [
                'key_title' => 'order_completed_title',
                'key_message' => 'order_completed_message',
                'icon' => asset('icons/complete.png'),
            ],
            4 => [
                'key_title' => 'order_closed_title',
                'key_message' => 'order_closed_message',
                'icon' => asset('icons/cancel.png'),
            ],
        ];


        if (isset($statusMessages[$order->status])) {
            // Update payment details for statuses that require full payment

            // Send notification
            $this->createNotification(
                $user,
                $statusMessages[$order->status]['key_title'],
                $statusMessages[$order->status]['key_message'],
                $link,
                $order->id,
                $type,
                $statusMessages[$order->status]['icon']
            );
        }

    }


    protected function createNotification($user, $title, $body, $link, $orderId, $type, $image)
    {
        // Store the current locale
        $currentLocale = app()->getLocale();

        // Set the locale to the user's language
        app()->setLocale($user->lang);

        // Send real-time notification via the notify method
        $user->notify(new OrderNotification(
            trans('notifications.'.$title),
            trans('notifications.'.  $body),
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

        app()->setLocale($currentLocale);

    }


    protected function AssurancehandleStatusUpdates($order, $request)
    {
        $user = $order->user;
        $link = route('api.assuranceRecords', $order->id); // Modify based on your actual route
        $payment = $order->payment ?? null;


        switch ($order->status) {

            case 1:

                $payment = Payment::create([
                    'user_id' => $order->user_id,
                    'payment_value' => $request->payment_value,
                    'order_value' => $order->value,
                    'remaining_amount' => $order->value - $request->payment_value,
                    'status' => 1,
                    'type' => 'assurance',
                    'payment_type' => 'Dashboard',
                    'order_id' => $order->id,
                ]);


                DashboardPayment::create([
                    'payment_id' => $payment->id,
                    'amount' => $request->payment_value,
                ]);


                $title = 'payment_processed_title';
                $message ='payment_processed_message';
                $image = asset('icons/payment-required.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'assurance', $image);
                break;

            case 2:

                if ($payment) {

                    DashboardPayment::create([
                        'payment_id' => $payment->id,
                        'amount' => $payment->remaining_amount,
                    ]);

                    $payment->update([
                        'remaining_amount' => 0,
                        'status' => 2, // fully paid
                        'payment_value' => $order->price,
                    ]);

                }


                $title = 'payment_processed_title';
                $message = 'payment_processed_message';
                $image = asset('icons/payment.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'assurance', $image);
                break;


            case 3:
                $remaining_amount = 0;
                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->price,
                ]);


                $title = 'order_completed_title';
                $message ='payment_processed_message';
                $image = asset('icons/complete.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'assurance', $image);
                break;


            case 4:
                $order->note = $request->note;
                $image = asset('icons/cancel.png');
                $title = 'order_closed_title';
                $message ='order_closed_message';
                $this->createNotification($user, $title, $message, $link, $order->id, 'assurance', $image);
                break;
        }

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
        DB::beginTransaction(); // Start transaction

        try {
            $user = $order->user;
            $link = route('api.violationRecords', $order->id);

            // Ensure payment record exists if needed
            $payment = $order->payment ?? null;

            switch ($order->status) {
                case 1: // Process payment
                    $payment = Payment::create([
                        'user_id' => $order->user_id,
                        'payment_value' => $request->payment_value,
                        'order_value' => $order->value,
                        'remaining_amount' => max(0, $order->value - $request->payment_value),
                        'status' => 1, // pending or partial payment
                        'type' => 'violation',
                        'payment_type' => 'Dashboard',
                        'order_id' => $order->id,
                    ]);

                    DashboardPayment::create([
                        'payment_id' => $payment->id,
                        'amount' => $request->payment_value,
                    ]);

                    $title = 'payment_processed_title';
                    $message = 'payment_processed_message';
                    $this->createNotification($user, $title, $message, $link, $order->id, 'violation', asset('icons/payment-required.png'));
                    break;


                case 2:

                    if ($payment) {

                        DashboardPayment::create([
                            'payment_id' => $payment->id,
                            'amount' => $payment->remaining_amount,
                        ]);

                        $payment->update([
                            'remaining_amount' => 0,
                            'status' => 2, // fully paid
                            'payment_value' => $order->value,
                        ]);

                        $title ='payment_processed_title';
                        $message = 'payment_processed_message';
                        $this->createNotification($user, $title, $message, $link, $order->id, 'violation', asset('icons/payment.png'));
                    } else {
                        throw new \Exception("No payment record found for order ID: {$order->id}");
                    }
                    break;

                case 3: // Complete payment and finalize order
                    if ($payment) {
                        $payment->update([
                            'remaining_amount' => 0,
                            'status' => 2, // fully paid
                            'payment_value' => $order->value,
                        ]);


                        $title = 'order_completed_title';
                        $message = 'payment_processed_message';
                        $this->createNotification(
                            $user,
                            $title,
                            $message,
                            $link,
                            $order->id,
                            'violation',
                            asset('icons/complete.png')
                        );
                    } else {
                        throw new \Exception("No payment record found for order ID: {$order->id}");
                    }
                    break;

                case 4: // Close the order with a note
                    $order->update(['note' => $request->note]);
                    $title = 'order_closed_title';
                    $message = 'order_closed_message';
                    $this->createNotification($user, $title, $message, $link, $order->id, 'violation', asset('icons/cancel.png'));
                    break;

                default:
                    throw new \Exception("Invalid status for order ID: {$order->id}");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating violation status for order ID {$order->id}: " . $e->getMessage());

            return response()->json(['error' => 'An error occurred while updating order status.'], 500);
        }
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


        switch ($order->status) {
            case 1:
                // Create payment record
                $payment = Payment::create([
                    'user_id' => $order->user_id,
                    'payment_value' => $request->payment_value,
                    'order_value' => $order->value,
                    'remaining_amount' => $order->value - $request->payment_value,
                    'status' => 1,
                    'type' => 'housekeeper',
                    'payment_type' => 'Dashboard',
                    'order_id' => $order->id,
                ]);

                DashboardPayment::create([
                    'payment_id' => $payment->id,
                    'amount' => $payment->remaining_amount,
                ]);

                $title = 'payment_processed_title';
                $message = 'payment_processed_message';
                $image = asset('icons/payment-required.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'housekeeper', $image);
                break;


            case 2:
                $payment = $order->payment;
                $remaining_amount = 0;

                DashboardPayment::create([
                    'payment_id' => $payment->id,
                    'amount' => $payment->remaining_amount,
                ]);


                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $title = 'payment_processed_title';
                $message = 'payment_processed_message';
                $image = asset('icons/payment.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'housekeeper', $image);
                break;


            case 3:

                $remaining_amount = 0;
                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $order->housekeeper->update(['status' => 1,]);

                $title = 'order_completed_title';
                $message = 'payment_processed_message';
                $image = asset('icons/complete.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'housekeeper', $image);
                break;


            case 4:
                $title = 'order_closed_title';
                $message = 'order_closed_message';
                $image = asset('icons/cancel.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'housekeeper', $image);
                break;
        }


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

            case 1:

                $payment = Payment::create([
                    'user_id' => $order->user_id,
                    'payment_value' => $request->payment_value,
                    'order_value' => $order->value,
                    'remaining_amount' => $order->value - $request->payment_value,
                    'status' => 1,
                    'type' => 'housekeeper_hourly_order',
                    'payment_type' => 'Dashboard',
                    'order_id' => $order->id,
                ]);

                DashboardPayment::create([
                    'payment_id' => $payment->id,
                    'amount' => $payment->remaining_amount,
                ]);

                $title = 'payment_processed_title';
                $message = 'payment_processed_message';
                $image = asset('icons/payment-required.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'housekeeper_hourly_order', $image);
                break;

            case 2:

                $payment = $order->payment;
                DashboardPayment::create([
                    'payment_id' => $payment->id,
                    'amount' => $payment->remaining_amount,
                ]);

                $remaining_amount = 0;

                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $title = 'payment_processed_title';
                $message = 'payment_processed_message';
                $image = asset('icons/payment-required.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'housekeeper_hourly_order', $image);

                break;


            case 3:
                $order->house_keeper_id = $request->housekeeper_id;
                $remaining_amount = 0;
                $order->payment->update([
                    'remaining_amount' => $remaining_amount,
                    'status' => 2,
                    'payment_value' => $order->value,
                ]);


                $title = 'order_completed_title';
                $message = 'payment_processed_message';
                $image = asset('icons/complete.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'housekeeper_hourly_order', $image);

                break;


            case 4:
                $title = 'order_closed_title';
                $message ='order_closed_message';
                $image = asset('icons/cancel.png');
                $this->createNotification($user, $title, $message, $link, $order->id, 'housekeeper_hourly_order', $image);
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
