<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CounselingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $counselingType,
        public string $topic,
        public string $scheduledAt,
        public string $url
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat Sesi Bimbingan')
            ->line('Anda memiliki sesi bimbingan yang akan datang.')
            ->line('Jenis: '.$this->counselingType)
            ->line('Topik: '.$this->topic)
            ->line('Jadwal: '.$this->scheduledAt)
            ->action('Lihat Detail', $this->url)
            ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'counseling_type' => $this->counselingType,
            'topic' => $this->topic,
            'scheduled_at' => $this->scheduledAt,
            'url' => $this->url,
        ];
    }
}
