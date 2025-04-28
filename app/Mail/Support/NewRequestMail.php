<?php

namespace App\Mail\Support;

use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly User $requester, public readonly User $staff, public readonly SupportRequest $supportRequest)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A new support request has been submitted',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.support.new-request-mail',
            with: [
                'requester' => $this->requester,
                'staff' => $this->staff,
                'supportRequest' => $this->supportRequest,
                'supportRequestUrl' => route('support.show', $this->supportRequest),
            ],
        );
    }
}
