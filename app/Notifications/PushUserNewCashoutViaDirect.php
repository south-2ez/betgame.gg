<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PushUserNewCashoutViaDirect extends Notification
{

    use Queueable;

    protected $userData;

    public function __construct($cashout)
    {
        $this->cashout = $cashout;
    }
    
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        $code = $this->cashout->code;
        $user_id = $this->cashout->user_id;
        $amount = $this->cashout->amount;

        return (new WebPushMessage)
            ->title('ALERT! New CASHOUT request.')
            ->icon('/notification-icon.png')
            ->body("CO-CODE: {$code} by User ID: {$user_id}, Amount: {$amount}")
            ->requireInteraction(true)
            ->action('OK', 'notification_action');
    }
    
}