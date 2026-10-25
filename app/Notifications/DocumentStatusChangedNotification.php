<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentStatusChangedNotification extends Notification
{
    use Queueable;

    public $document;
    public $action; // 'routed', 'received', 'released', 'completed'
    public $actionBy; // User who performed the action

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, string $action, User $actionBy)
    {
        $this->document = $document;
        $this->action = $action;
        $this->actionBy = $actionBy;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $trackingNo = $this->document->tracking_number;
        $subject = $this->action === 'incoming'
            ? "DTS Alert: New Incoming Document #{$trackingNo}"
            : "DTS Alert: Document #{$trackingNo} Marked as Completed";

        $messageLine = $this->action === 'incoming'
            ? "A new document titled **\"{$this->document->title}\"** (#{$trackingNo}) has been routed to your office by {$this->actionBy->name} and is awaiting action."
            : "Document titled **\"{$this->document->title}\"** (#{$trackingNo}) has been marked as **Completed** by {$this->actionBy->name}.";

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line($messageLine)
            ->action('View Document Details', route('documents.show', $this->document->id))
            ->line('Thank you for using the Division Document Tracking System!');
    }

    public function toArray($notifiable): array
    {
        $isIncoming = $this->action === 'incoming';

        return [
            'title' => $isIncoming ? 'Incoming Document' : 'Document Completed',
            'message' => $isIncoming
                ? "Doc #{$this->document->tracking_number} was routed to your office by {$this->actionBy->name}."
                : "Doc #{$this->document->tracking_number} was marked as completed by {$this->actionBy->name}.",
            'document_id' => $this->document->id,
            'tracking_number' => $this->document->tracking_number,
            'url' => route('documents.show', $this->document->id),
            'icon' => $isIncoming ? 'inbox' : 'check-circle',
            'type' => 'document_' . $this->action,
        ];
    }
}
