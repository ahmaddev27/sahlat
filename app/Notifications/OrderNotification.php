<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class OrderNotification extends Notification
{
    protected $title;
    protected $body;
    protected $image;
    protected $link;
    protected $type;
    protected $orderId;

    public function __construct(string $title, string $body, $link, $orderId, $type, $image)
    {


        $this->title = $title;
        $this->body = $body;
        $this->link = $link;
        $this->orderId = $orderId;
        $this->image = $image;
        $this->type = $type;



    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }



//     Define the FCM message structure
    public function toFcm($notifiable): FcmMessage
    {

        return (new FcmMessage(notification: new FcmNotification(
            $this->title,
            $this->body,
            url($this->image)
        )))->data(['type' => $this->type,'order_id'=>(string)$this->orderId]);


    }






}
