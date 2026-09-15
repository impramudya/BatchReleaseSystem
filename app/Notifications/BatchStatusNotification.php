<?php

namespace App\Notifications;

use App\Models\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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

        return $notifiable->email
            ? ['database', 'mail']
            : ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($this->message)
            ->line('Detail batch:')
            ->line('Nomor Batch: ' . $this->batch->batch_number)
            ->line('Produk: ' . ($this->batch->product->name ?? '-'))
            ->line('Tanggal Batch: ' . ($this->batch->batch_date?->format('d/m/Y') ?? '-'))
            ->action('Buka BatchReleasePro', $this->url)
            ->line('Email ini dikirim otomatis oleh sistem BatchReleasePro.')
            ->salutation('Terima kasih.');
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
