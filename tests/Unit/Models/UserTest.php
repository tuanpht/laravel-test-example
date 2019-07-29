<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTest extends TestCase
{
    public function testPasswordWillBeHashed()
    {
        Hash::shouldReceive('make')->once()->andReturn('hashed');

        $user = new User([
            'name' => 'User',
            'password' => 'rawpassword',
        ]);

        $this->assertEquals('hashed', $user->password);
    }

    /**
     * @dataProvider provideEmptyPassword
     */
    public function testPasswordWillNotBeHashedIfEmpty($password)
    {
        $user = new User([
            'name' => 'User',
            'password' => $password,
        ]);

        $this->assertNull($user->password);
    }

    public function provideEmptyPassword()
    {
        return [
            [''],
            [null],
        ];
    }

    public function testCheckUserEmailVefified()
    {
        $user = new User();
        $user->email_verified_at = now();

        $this->assertTrue($user->hasVerifiedEmail());
    }

    public function testCheckUserEmailNotVefified()
    {
        $user = new User();

        $this->assertFalse($user->hasVerifiedEmail());
    }
}
