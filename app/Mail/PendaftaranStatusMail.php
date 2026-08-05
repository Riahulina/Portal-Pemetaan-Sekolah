<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendaftaranStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaSekolah,
        public string $npsn,
        public string $jenjang,
        public string $provinsi,
        public string $kabupatenKota,
        public string $status,
        public string $catatanAdmin,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->status === 'disetujui'
                ? 'Pendaftaran Sekolah Disetujui'
                : 'Pendaftaran Sekolah Ditolak',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.pendaftaran-status');
    }
}
