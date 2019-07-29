<?php

namespace Tests\Feature\Service\Web;

use Tests\TestCase;
use Tests\Integration\SetupDatabaseTrait;
use App\Services\Web\UserService;
use App\Models\User;

class UserServiceTest extends TestCase
{
    use SetupDatabaseTrait;

    public function testCreateSuccess()
    {
        $inputs = [
            'name' => 'Name',
            'email' => 'user@example.com',
            'password' => '12345678',
        ];

        $user = (new UserService)->create($inputs);

        $this->assertNotNull($user->id);
    }

    public function testFindByIdReturnNull()
    {
        $userNotFound = (new UserService)->findById(1111);

        $this->assertNull($userNotFound);
    }

    public function testFindByIdReturnUser()
    {
        $existedUser = factory(User::class)->create();

        $user = (new UserService)->findById($existedUser->id);

        $this->assertEquals($existedUser->id, $user->id);
        $this->assertEquals($existedUser->email, $user->email);
    }

    public function testFindByEmailReturnNull()
    {
        $userNotFound = (new UserService)->findByEmail('nonexisted@email');

        $this->assertNull($userNotFound);
    }

    public function testFindByEmailReturnUser()
    {
        $existedEmail = 'user@example.com';
        $existedUser = factory(User::class)->create([
            'email' => $existedEmail,
        ]);

        $user = (new UserService)->findByEmail($existedEmail);

        $this->assertEquals($existedUser->id, $user->id);
        $this->assertEquals($existedUser->email, $user->email);
    }

    public function testVerifyUserSuccess()
    {
        $notVerifiedUser = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $result = (new UserService)->verifyUser($notVerifiedUser);

        $this->assertTrue($result);
        $this->assertTrue($notVerifiedUser->hasVerifiedEmail());
    }
}
