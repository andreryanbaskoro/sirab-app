<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Permintaan;

class PermintaanNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Permintaan $permintaan,
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
            'permintaan_id' => $this->permintaan->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => 'permintaan',
        ];
    }
}
