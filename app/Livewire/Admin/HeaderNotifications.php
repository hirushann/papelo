<?php

namespace App\Livewire\Admin;

use App\Models\ContactSubmission;
use Livewire\Component;

class HeaderNotifications extends Component
{
    public $notifications = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = [];

        // Fetch real unread contact submissions
        $unreadSubmissions = ContactSubmission::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($unreadSubmissions as $submission) {
            $this->notifications[] = [
                'id' => 'contact_' . $submission->id,
                'title' => 'New Contact Message',
                'description' => $submission->name . ' sent a message: ' . str()->limit($submission->message, 30),
                'time' => $submission->created_at->diffForHumans(),
                'unread' => true,
                'type' => 'user',
                'link' => route('admin.messages')
            ];
        }

        // Fetch real unread database notifications
        $user = auth()->user();
        if ($user) {
            foreach ($user->unreadNotifications as $notification) {
                $this->notifications[] = [
                    'id' => 'db_' . $notification->id,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'description' => $notification->data['description'] ?? '',
                    'time' => $notification->created_at->diffForHumans(),
                    'unread' => true,
                    'type' => $notification->data['type'] ?? 'user',
                    'link' => $notification->data['link'] ?? '#'
                ];
            }
        }
    }

    public function markAsRead($id)
    {
        if (str_starts_with($id, 'contact_')) {
            $realId = str_replace('contact_', '', $id);
            $submission = ContactSubmission::find($realId);
            if ($submission) {
                $submission->is_read = true;
                $submission->save();
            }
        } elseif (str_starts_with($id, 'db_')) {
            $realId = str_replace('db_', '', $id);
            $user = auth()->user();
            if ($user) {
                $notification = $user->unreadNotifications()->where('id', $realId)->first();
                if ($notification) {
                    $notification->markAsRead();
                }
            }
        }

        foreach ($this->notifications as $key => $notification) {
            if ($notification['id'] == $id) {
                $this->notifications[$key]['unread'] = false;
            }
        }
    }

    public function markAllAsRead()
    {
        ContactSubmission::where('is_read', false)->update(['is_read' => true]);

        $user = auth()->user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        foreach ($this->notifications as $key => $notification) {
            $this->notifications[$key]['unread'] = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.header-notifications');
    }
}
