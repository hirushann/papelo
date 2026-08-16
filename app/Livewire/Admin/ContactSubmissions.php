<?php

namespace App\Livewire\Admin;

use App\Models\ContactSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class ContactSubmissions extends Component
{
    use WithPagination;

    public ?ContactSubmission $viewingSubmission = null;

    public function viewMessage($id)
    {
        $this->viewingSubmission = ContactSubmission::find($id);
        
        if ($this->viewingSubmission && !$this->viewingSubmission->is_read) {
            $this->viewingSubmission->is_read = true;
            $this->viewingSubmission->save();
        }

        \Flux::modal('view-message')->show();
    }

    public function markAsRead($id)
    {
        $submission = ContactSubmission::find($id);
        if ($submission) {
            $submission->is_read = true;
            $submission->save();
        }
    }

    public function markAsUnread($id)
    {
        $submission = ContactSubmission::find($id);
        if ($submission) {
            $submission->is_read = false;
            $submission->save();
        }
    }

    public function render()
    {
        $submissions = ContactSubmission::orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.admin.contact-submissions', [
            'submissions' => $submissions
        ])->layout('layouts.admin', ['header' => 'Messages']);
    }
}
