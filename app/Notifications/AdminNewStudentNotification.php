<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminNewStudentNotification extends Notification
{
    use Queueable;

    public $student;

    public function __construct(User $student)
    {
        $this->student = $student;
    }

    public function via(object $notifiable): array
    {
        $setting = Setting::where('key', 'notifyNewStudent')->first();
        $isEnabled = $setting ? (bool) $setting->value : true; // Default true

        return $isEnabled ? ['database'] : [];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New student sign-up',
            'description' => "{$this->student->name} just registered an account.",
            'type' => 'user',
            'link' => route('admin.users')
        ];
    }
}
