<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class salf_notiy extends Notification
{
    use Queueable;
public $d;



    /**
     * Create a new notification instance.
     */
    public function __construct($d)
    {
        $this->d = $d;

    }

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


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "masg"=> $this->d["masg"] ,
            "id"=> $this->d["id"],
            "date"=> $this->d["date"],
            "dec"=> $this->d["dec"],
            "type"=> $this->d["type"],
            "user"=> Auth::user()->name,
        ];
    }
}
