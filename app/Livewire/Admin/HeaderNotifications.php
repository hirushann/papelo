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

        // Mock notifications for the UI (can be removed later when other systems are real)
        $mockNotifications = [
            [
                'id' => 'mock_1',
                'title' => 'New student sign-up',
                'description' => 'John Doe just registered an account.',
                'time' => '2 mins ago',
                'unread' => true,
                'type' => 'user'
            ],
            [
                'id' => 'mock_2',
                'title' => 'Payment successful',
                'description' => 'Jane Smith purchased Grade 5 Model Paper.',
                'time' => '1 hour ago',
                'unread' => true,
                'type' => 'payment'
            ],
            [
                'id' => 'mock_3',
                'title' => 'Failed payment',
                'description' => 'A payment for O/L Science failed.',
                'time' => '2 hours ago',
                'unread' => false,
                'type' => 'error'
            ],
        ];

        // Merge them
        $this->notifications = array_merge($this->notifications, $mockNotifications);
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

        foreach ($this->notifications as $key => $notification) {
            $this->notifications[$key]['unread'] = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.header-notifications');
    }
}
