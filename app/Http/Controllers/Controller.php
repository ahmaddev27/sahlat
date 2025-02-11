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
                'order',
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

}
