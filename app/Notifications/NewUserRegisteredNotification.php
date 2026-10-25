<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;

    public $newUser;
    /**
     * Create a new notification instance.
     */
    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // Deliver via Database and Mail
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    // Email Layout
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('DDTS: New Account Registration Pending Approval')
            ->greeting('Hello Admin,')
            ->line("A new user account ({$this->newUser->name}) has registered and requires your approval.")
            ->line("Email: {$this->newUser->email}")
            ->line("Office/School: " . ($this->newUser->office->name ?? 'N/A'))
            ->action('Review User Accounts', route('users.index', ['status' => 'pending']))
            ->line('Thank you for using Division Document Tracking System!');
    }

    // Database Payload (for AdminKit Navbar Dropdown)
    public function toArray($notifiable): array
    {
        // Safely retrieve office name using optional() or null coalescing
        $officeName = optional($this->newUser->office)->name ?? 'No Office Assigned';

        return [
            'title' => 'New User Registration',
            'message' => "{$this->newUser->name} ({$officeName}) registered and is pending approval.",
            'user_id' => $this->newUser->id,
            'url' => route('users.index', ['status' => 'pending']),
            'icon' => 'user-plus',
            'type' => 'user_registered',
        ];
    }
}
