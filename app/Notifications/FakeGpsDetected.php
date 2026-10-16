<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FakeGpsDetected extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $numid,
        public readonly string $empName,
        public readonly string $detectedAt,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'fake_gps',
            'numid'       => $this->numid,
            'emp_name'    => $this->empName,
            'detected_at' => $this->detectedAt,
            'message'     => "🚫 تم اكتشاف Fake GPS للموظف: {$this->empName} (رقم: {$this->numid})",
        ];
    }
}
