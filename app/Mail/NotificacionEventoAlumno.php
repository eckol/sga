<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

// Al implementar ShouldQueue, Laravel mandará el mail en segundo plano automáticamente
class NotificacionEventoAlumno extends Mailable implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $alumnoNom;
    public $tipoEvento;
    public $detalleEvento;

    public function __construct($alumnoNom, $tipoEvento, $detalleEvento)
    {
        $this->alumnoNom = $alumnoNom;
        $this->tipoEvento = $tipoEvento;
        $this->detalleEvento = $detalleEvento;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "SGA CST: Nuevo registro de {$this->tipoEvento} - {$this->alumnoNom}",
        );
    }

    public function content(): \Illuminate\Mail\Mailables\Content
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.notificacion_evento',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(base_path('public/img/logo_cst_login.png'))
                ->as('logo_cst_login.png')
                ->withMime('image/png'),
        ];
    }
}