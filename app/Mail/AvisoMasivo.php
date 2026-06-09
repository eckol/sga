<?php

namespace App\Mail;

use App\Models\Aviso;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AvisoMasivo extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Aviso $aviso)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->aviso->titulo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.aviso_masivo',
        );
    }

    public function attachments(): array
    {
        if (!$this->aviso->archivo_adjunto) {
            return [];
        }

        $path = Storage::disk('public')->path($this->aviso->archivo_adjunto);

        if (!file_exists($path)) {
            return [];
        }

        return [
            Attachment::fromPath($path)
                ->as(basename($path))
                ->withMime(mime_content_type($path) ?: 'application/octet-stream'),
        ];
    }
}