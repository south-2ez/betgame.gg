<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PushUserNewReportedBug extends Notification
{

    use Queueable;

    protected $userData;

    public function __construct($bug)
    {
        $this->bug = $bug;
    }
    
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        $subject = $this->bug->subject;
        $user_id = $this->bug->user_id;

        return (new WebPushMessage)
            ->title('ALERT! New Reported Bug')
            ->icon('/notification-icon.png')
            ->body("Subject: {$subject} reported by User ID: {$user_id}")
            ->requireInteraction(true)
            ->action('OK', 'notification_action');
    }
    
}