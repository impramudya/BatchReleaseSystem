<?php

namespace App\Notifications;

use App\Models\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BatchStatusNotification extends Notification
{
    use Queueable;

    /**
     * @param string $title
     * @param string $message
     * @param string $url
     */
    public function __construct(
        public Batch $batch,
        public string $title,
        public string $message,
        public string $url,
    ) {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'batch_id' => $this->batch->id,
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
        ];
    }
}
