<?php

namespace Tests\Feature\Http\Controllers\Api\Profile;

use Carbon\Carbon;
use Tests\Feature\ApiTestCase;
use Tagydes\Transformers\UserTransformer;
use Tagydes\User;

class AuthDetailsControllerTest extends ApiTestCase
{
    public function test_user_can_update_his_authentication_details()
    {
        $user = $this->login();

        $this->patch('/api/me/details/auth', [
            'email' => 'foo@example.com',
            'username' => 'john.doe',
            'password' => '123123',
            'password_confirmation' => '123123'
        ]);

        $this->assertResponseOk()
            ->seeJsonContains(['email' => 'foo@example.com', 'username' => 'john.doe'])
            ->assertTrue(password_verify('123123', $user->fresh()->password));
    }

    public function test_user_can_update_only_email_and_leave_other_fields_unchanged()
    {
        $user = $this->login();

        $this->patch('/api/me/details/auth', [
            'email' => 'foo@example.com',
        ]);

        $this->assertResponseOk()
            ->seeJsonContains(['email' => 'foo@example.com']);

        $this->assertEquals($user->username, $user->fresh()->username);
        $this->assertEquals($user->password, $user->fresh()->password);
    }

    public function test_email_field_is_required()
    {
        $this->login();

        $this->patch('/api/me/details/auth')
            ->assertValidationErrorKeyExists('email');
    }

    public function test_email_field_must_be_valid_email()
    {
        $this->login();

        $this->patch('/api/me/details/auth', [
            'email' => 'invalid email'
        ])->assertValidationErrorKeyExists('email');
    }

    public function test_email_field_must_be_unique()
    {
        $user = $this->login();

        $user2 = $this->createUser(['email' => 'john.doe@test.com']);

        $this->patch('/api/me/details/auth', [
            'email' => 'john.doe@test.com',
        ])->assertValidationErrorKeyExists('email');
    }

    public function test_username_field_must_be_unique()
    {
        $user = $this->login();

        $user2 = $this->createUser(['username' => 'john.doe']);

        $this->patch('/api/me/details/auth', [
            'email' => 'john.doe@test.com',
            'username' => 'john.doe'
        ])->assertValidationErrorKeyExists('username');
    }

    private function assertValidationErrorKeyExists($key)
    {
        $this->assertResponseStatus(422);
        $this->assertArrayHasKey($key, $this->decodeResponseJson());
    }
}
