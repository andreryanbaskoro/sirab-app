<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Kontrak;

class KontrakNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Kontrak $kontrak,
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
            'kontrak_id' => $this->kontrak->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => 'kontrak',
        ];
    }
}
