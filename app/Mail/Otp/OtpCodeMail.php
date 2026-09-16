<?php

namespace App\Mail\Otp;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $code)
    {
        //
    }
    public function build()
    {
        return $this->subject('Your verification code')
            ->view('emails.otp-code')
            ->with(['code' => $this->code]);
    }

}
