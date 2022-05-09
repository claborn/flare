<?php

namespace App\Flare\Mail;

use App\Admin\Mail\UnBanRequestMail;
use Illuminate\Bus\Queueable;
use Asahasrabuddhe\LaravelMJML\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Flare\Models\User;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    private $toEmail;

    private $mailable;

    /**
     * @param string $toEmail
     * @param Mailable $mailable
     */
    public function __construct(string $toEmail, Mailable $mailable)
    {
        $this->to       = $toEmail;
        $this->mailable = $mailable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Mail::to($this->toEmail)->send($this->mailable);
    }
}