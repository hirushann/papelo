<?php

namespace App\Notifications;

use App\Models\Paper;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminPaperPublishedNotification extends Notification
{
    use Queueable;

    public $paper;

    public function __construct(Paper $paper)
    {
        $this->paper = $paper;
    }

    public function via(object $notifiable): array
    {
        $setting = Setting::where('key', 'notifyNewPaper')->first();
        $isEnabled = $setting ? (bool) $setting->value : false; // Default false as per original

        return $isEnabled ? ['database'] : [];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New paper published',
            'description' => "'{$this->paper->title}' is now live.",
            'type' => 'success',
            'link' => route('admin.papers')
        ];
    }
}
