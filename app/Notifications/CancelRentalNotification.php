<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CancelRentalNotification extends Notification
{
    use Queueable;

    protected $rental;
    protected $reason;

    public function __construct($rental, $reason)
    {
        $this->rental = $rental;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail']; // หรือเพิ่ม 'database' ถ้าต้องการแจ้งในระบบ
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('มีการยกเลิกคำสั่งเช่า')
                    ->line('ผู้ใช้ ' . $this->rental->user->name . ' ได้ยกเลิกคำสั่งเช่า:')
                    ->line('ชุด: ' . $this->rental->costume->name)
                    ->line('เหตุผล: ' . $this->reason)
                    ->action('ดูรายละเอียด', url('/admin/rentals'))
                    ->line('กรุณาตรวจสอบในระบบ');
    }
}