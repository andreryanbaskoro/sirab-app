<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Rab;

class RabNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Rab $rab,
        public string $title,
        public string $message
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'rab_id' => $this->rab->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => 'rab',
        ];
    }
}
