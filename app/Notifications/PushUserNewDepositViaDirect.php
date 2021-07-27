<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PushUserNewDepositViaDirect extends Notification
{

    use Queueable;

    protected $userData;

    public function __construct($deposit)
    {
        $this->deposit = $deposit;
    }
    
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        $code = $this->deposit->code;
        $user_id = $this->deposit->user_id;
        $amount = $this->deposit->amount;

        return (new WebPushMessage)
            ->title('ALERT! New DEPOSIT request.')
            ->icon('/notification-icon.png')
            ->body("BC-CODE: {$code} by User ID: {$user_id}, Amount: {$amount}")
            ->requireInteraction(true)
            ->action('OK', 'notification_action');
    }
    
}