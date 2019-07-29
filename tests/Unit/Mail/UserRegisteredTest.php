<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use App\Mail\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisteredTest extends TestCase
{
    public function testMailShouldBeQueuedByDefault()
    {
        $mailable = new UserRegistered(1, 'Test user name');

        $this->assertInstanceOf(ShouldQueue::class, $mailable);
    }

    public function testBuildMarkdownMail()
    {
        $mailable = new UserRegistered(1, 'Test user name');

        $mailable->build();

        $this->assertArrayHasKey('url', $mailable->viewData);
        $this->assertEquals('Test user name', $mailable->viewData['userName']);
        $this->assertEquals('Verify your account', $mailable->subject);
    }
}
