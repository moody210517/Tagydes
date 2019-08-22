<?php

namespace Tests\Feature\Http\Controllers\Api\Profile;

use Authy;
use Mockery;
use Settings;
use Tests\Feature\ApiTestCase;
use Tagydes\Events\User\TwoFactorEnabled;
use Tagydes\Transformers\UserTransformer;
use Tagydes\User;

class TwoFactorControllerTest extends ApiTestCase
{
    public function test_update_2fa_unathenticated()
    {
        $this->setSettings([
            '2fa.enabled' => true
        ]);

        $user = factory(User::class)->create();

        $this->putJson("api/me/2fa")
            ->assertResponseStatus(401);
    }

    public function test_enable_two_factor_auth()
    {
        $this->setSettings([
            '2fa.enabled' => true
        ]);

        $this->withoutExceptionHandling();

        $this->doesntExpectEvents(TwoFactorEnabled::class);

        $user = $this->login();

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('register')->andReturnNull();
        Authy::shouldReceive('sendTwoFactorVerificationToken');

        $data = ['country_code' => '1', 'phone_number' => '123'];
 
        $this->putJson("api/me/2fa", $data);

        $this->assertResponseOk()
            ->seeJson(['message' => 'Verification token sent.'])
            ->seeInDatabase('users', [
                'id' => $user->id,
                'two_factor_country_code' => $data['country_code'],
                'two_factor_phone' => $data['phone_number']
            ]);
    }

    /** @test */
    public function verify_user_phone_with_correct_token()
    {
        $this->setSettings([
            '2fa.enabled' => true
        ]);

        $this->expectsEvents(TwoFactorEnabled::class);

        $user = $this->login();

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('tokenIsValid')->with(Mockery::any(), '123123')->andReturn(true);

        $this->postJson("api/me/2fa/verify", ['token' => '123123']);

        $transformer = new UserTransformer;
        $updatedUser = $transformer->transform($user->fresh());

        $this->assertResponseOk()
            ->seeInDatabase('users', [
                'id' => $user->id,
                'two_factor_options' => '{"enabled":true}'
            ])
            ->seeJsonContains($updatedUser);
    }

    /** @test */
    public function verify_user_phone_with_invalid_token()
    {
        $this->setSettings([
            '2fa.enabled' => true
        ]);

        $user = $this->login();

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('tokenIsValid')->andReturn(false);

        $this->postJson("api/me/2fa/verify", ['token' => '123123']);

        $this->assertResponseStatus(422)
            ->seeJsonContains(['error' => 'Invalid 2FA token.'])
            ->dontSeeInDatabase('users', [
                'id' => $user->id,
                'two_factor_options' => '{"enabled":true}'
            ]);
    }

    public function test_enable_two_factor_auth_when_it_is_already_enabled()
    {
        $this->setSettings([
            '2fa.enabled' => true
        ]);

        $user = $this->login();

        Authy::shouldReceive('isEnabled')->andReturn(true);

        $data = ['country_code' => '1', 'phone_number' => '123'];

        $this->putJson("api/me/2fa", $data);

        $this->assertResponseStatus(422)
            ->seeJsonContains([
                'error' => '2FA is already enabled for this user.'
            ]);
    }

    public function test_disable_two_factor_auth()
    {
        $this->setSettings([
            '2fa.enabled' => true
        ]);

        $user = factory(User::class)->create([
            'two_factor_country_code' => '1',
            'two_factor_phone' => '123'
        ]);

        $this->be($user, 'api');

        Authy::shouldReceive('isEnabled')->andReturn(true);
        Authy::shouldReceive('delete')->andReturnNull();

        $this->deleteJson("api/me/2fa");

        $transformer = new UserTransformer;
        $user = $transformer->transform($user->fresh());

        $this->assertResponseOk()
            ->seeJsonContains($user);
    }

    public function test_disable_2fa_when_it_is_already_disabled()
    {
        $this->setSettings([
            '2fa.enabled' => true
        ]);

        $user = $this->login();

        Authy::shouldReceive('isEnabled')->andReturn(false);

        $this->deleteJson("api/me/2fa");

        $this->assertResponseStatus(422)
            ->seeJsonContains([
                'error' => '2FA is not enabled for this user.'
            ]);
    }
}
