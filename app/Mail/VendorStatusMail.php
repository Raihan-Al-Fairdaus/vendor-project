<?php

namespace App\Mail;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendor;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Vendor $vendor, $status)
    {
        $this->vendor = $vendor;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $subject = $this->status === 'approved' 
            ? 'Selamat! Pendaftaran Vendor Anda Disetujui - DNA Advertising' 
            : 'Pemberitahuan Status Pendaftaran Vendor - DNA Advertising';
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.vendor-status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
