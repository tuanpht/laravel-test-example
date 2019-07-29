<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class UserRegistered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $userId;

    protected $userName;

    public function __construct($userId, $userName)
    {
        $this->userId = $userId;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.registered')
            ->subject('Verify your account')
            ->with([
                'url' => $this->verificationUrl(),
                'userName' => $this->userName,
            ]);
    }

    private function verificationUrl()
    {
        return URL::temporarySignedRoute(
            'web.register.verify',
            now()->addMinutes(60),
            ['id' => $this->userId]
        );
    }
}
