<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PushUserNegativeCredits extends Notification
{

    use Queueable;

    protected $userData;

    public function __construct($userData)
    {
        $this->userData = $userData;
    }
    
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        $name = $this->userData->name;
        $user_id = $this->userData->id;
        $credits = $this->userData->credits;

        return (new WebPushMessage)
            ->title('ALERT! New negative credits User!')
            ->icon('/notification-icon.png')
            ->body("User: {$name} with ID: {$user_id}, credits: {$credits}")
            ->requireInteraction(true)
            ->action('OK', 'notification_action');
    }
    
}