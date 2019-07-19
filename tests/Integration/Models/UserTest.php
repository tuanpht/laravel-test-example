<?php

namespace Tests\Integration\Models;

use Tests\TestCase;
use App\Models\User;
use Tests\Integration\SetupDatabaseTrait;
use Carbon\Carbon;

class UserTest extends TestCase
{
    use SetupDatabaseTrait;

    public function testFieldsAreFillable()
    {
        $inputs = [
            'name' => 'Name',
            'email' => 'email@example.com',
            'password' => 'password',
            'remember_token' => 'should not be fillable',
        ];

        $user = User::create($inputs);

        $this->assertEquals($inputs['name'], $user->name);
        $this->assertEquals($inputs['email'], $user->email);
        $this->assertNotNull($user->password);
        $this->assertNull($user->remember_token);
    }

    public function testFieldsAreHidden()
    {
        $user = factory(User::class)->create();

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    public function testFieldEmailVefifiedAtCastedToNull()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $this->assertNull($user->email_verified_at);
    }

    public function testFieldEmailVefifiedAtCastedToDateTime()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => '2019-11-11 11:11:11',
        ]);

        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);
    }
}
