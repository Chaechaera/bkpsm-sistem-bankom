<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsulanStatusChanged extends Notification
{
    use Queueable;

    protected $usulankegiatan;

    /**
     * Create a new notification instance.
     */
    public function __construct($usulankegiatan) 
    { 
        $this->usulankegiatan = $usulankegiatan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Status Usulan: ' . $this->usulankegiatan->nama_kegiatan)
            ->line('Status usulan Anda telah berubah menjadi: ' . $this->usulankegiatan->statususulan_kegiatan)
            ->action('Lihat Usulan', url('/usulan'))
            ->line('Terimakasih');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'usulan_id' => $this->usulankegiatan->id,
            'title' => 'Status usulan diperbarui',
            'message' => 'Usulan "' . $this->usulankegiatan->nama_kegiatan . '" sekarang: ' . $this->usulankegiatan->statususulan_kegiatan,
        ];
    }
}
