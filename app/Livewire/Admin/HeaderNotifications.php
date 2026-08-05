<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class HeaderNotifications extends Component
{
    public $notifications = [];

    public function mount()
    {
        // Mock notifications for the UI
        $this->notifications = [
            [
                'id' => 1,
                'title' => 'New student sign-up',
                'description' => 'John Doe just registered an account.',
                'time' => '2 mins ago',
                'unread' => true,
                'type' => 'user'
            ],
            [
                'id' => 2,
                'title' => 'Payment successful',
                'description' => 'Jane Smith purchased Grade 5 Model Paper.',
                'time' => '1 hour ago',
                'unread' => true,
                'type' => 'payment'
            ],
            [
                'id' => 3,
                'title' => 'Failed payment',
                'description' => 'A payment for O/L Science failed.',
                'time' => '2 hours ago',
                'unread' => false,
                'type' => 'error'
            ],
        ];
    }

    public function markAsRead($id)
    {
        foreach ($this->notifications as $key => $notification) {
            if ($notification['id'] == $id) {
                $this->notifications[$key]['unread'] = false;
            }
        }
    }

    public function markAllAsRead()
    {
        foreach ($this->notifications as $key => $notification) {
            $this->notifications[$key]['unread'] = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.header-notifications');
    }
}
