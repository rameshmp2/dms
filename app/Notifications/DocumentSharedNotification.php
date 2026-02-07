<?php
// app/Notifications/DocumentSharedNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Document;

class DocumentSharedNotification extends Notification
{
    use Queueable;

    protected $document;
    protected $sharedBy;

    public function __construct(Document $document, $sharedBy)
    {
        $this->document = $document;
        $this->sharedBy = $sharedBy;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Document Shared: ' . $this->document->title)
            ->greeting('Hello ' . $notifiable->name)
            ->line($this->sharedBy->name . ' has shared a document with you.')
            ->line('Document: ' . $this->document->title)
            ->action('View Document', route('documents.show', $this->document->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'document_id' => $this->document->id,
            'document_title' => $this->document->title,
            'shared_by' => $this->sharedBy->name,
        ];
    }
}