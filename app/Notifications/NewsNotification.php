<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NewsNotification extends Notification
{
    protected $title;
    protected $body;
    protected $image;
    protected $type;


    public function __construct(string $title, string $body,$type,$image=null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->image = $image;
        $this->type = $type;
    }

    // Define the delivery channels
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }


    // Define the FCM message structure
    public function toFcm($notifiable): FcmMessage
    {

        // Return the FCM message with notification and custom data
        return (new FcmMessage(notification: new FcmNotification(
            $this->title,
            $this->body,
            $this->image

        )))->data(['type'=>$this->type]);


    }




}
