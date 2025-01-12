<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InformCustomerMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly User $user, public readonly int $purchasedTime, public readonly string $supportType, public readonly ?string $details, public readonly string $paymentId)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Support Time Purchase Confirmation'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.inform-customer-mail',
            with: [
                'user' => $this->user,
                'purchasedTime' => $this->purchasedTime,
                'supportType' => $this->supportType,
                'details' => $this->details,
                'paymentId' => $this->paymentId,
                'totalSupportTime' => $this->user->support_time_balance,
            ],
        );
    }
}
