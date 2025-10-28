<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FuelRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requestData;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pedido de Combustível - ' . $this->requestData['requested_at'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fuel-request',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}