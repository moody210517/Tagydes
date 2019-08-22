<?php

namespace Tests\Feature\Http\Controllers\Web;

use Authy;
use Tests\Feature\FunctionalTestCase;
use Tagydes\Events\User\TwoFactorEnabled;
use Tagydes\Events\User\TwoFactorEnabledByAdmin;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\User;

class TwoFactorControllerTest extends FunctionalTestCase
{
    /** @test */
    public function the_2fa_form_is_visible_on_profile_page_if_2fa_is_enabled()
    {
        config(['services.authy.key' => 'test']);

        $this->setSettings(['2fa.enabled' => false]);

        $this->createAndLoginUser();

        $this->visit("profile")
            ->dontSee('Two-Factor Authentication');

        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        $this->visit("profile")
            ->see('Two-Factor Authentication');
    }

    /** @test */
    public function the_2fa_form_is_visible_on_edit_user_page_if_2fa_is_enabled()
    {
        config(['services.authy.key' => 'test']);

        $this->setSettings(['2fa.enabled' => false]);

        $this->createAndLoginAdminUser();

        $user = factory(User::class)->create();

        $this->visit("/user/{$user->id}/edit")
            ->dontSee('Two-Factor Authentication');

        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginAdminUser();

        $user = factory(User::class)->create();

        $this->visit("/user/{$user->id}/edit")
            ->see('Two-Factor Authentication');
    }

    /** @test */
    public function enable_2fa_from_profile_page()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $user = $this->createAndLoginUser();

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('register')->andReturnNull();
        Authy::shouldReceive('sendTwoFactorVerificationToken')->andReturnNull();

        $this->visit("profile")
            ->submitForm('Enable', ['country_code' => '1', 'phone_number' => '123'])
            ->seePageIs("two-factor/verification")
            ->seeInDatabase('users', [
                'id' => $user->id,
                'two_factor_country_code' => 1,
                'two_factor_phone' => 123
            ]);
    }

    /** @test */
    public function enable_2fa_from_edit_user_page()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginAdminUser();

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('register')->andReturnNull();
        Authy::shouldReceive('sendTwoFactorVerificationToken')->andReturnNull();

        $user = factory(User::class)->create();

        $this->visit("/user/{$user->id}/edit")
            ->submitForm('Enable', ['country_code' => '1', 'phone_number' => '123'])
            ->seePageIs("two-factor/verification?user={$user->id}")
            ->seeInDatabase('users', [
                'id' => $user->id,
                'two_factor_country_code' => 1,
                'two_factor_phone' => 123
            ]);
    }

    /** @test */
    public function users_without_appropriate_permissions_cannot_enable_2fa_for_other_users()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('register')->andReturnNull();
        Authy::shouldReceive('sendTwoFactorVerificationToken')->andReturnNull();

        $user = factory(User::class)->create();

        $this->post('two-factor/enable', [
            'user' => $user->id,
            'country_code' => '1',
            'phone_number' => '123'
        ]);

        $this->assertResponseStatus(403)
            ->dontSeeInDatabase('users', [
                'id' => $user->id,
                'two_factor_country_code' => 1,
                'two_factor_phone' => 123
            ]);
    }

    /** @test */
    public function phone_verification_page_is_not_accessible_if_2fa_is_disabled_on_global_level()
    {
        $this->setSettings(['2fa.enabled' => false]);

        $this->createAndLoginUser();

        $this->get("two-factor/verification")
            ->assertResponseStatus(404);
    }

    /** @test */
    public function phone_verification_page_is_not_accessible_if_user_phone_is_not_set()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $user1 = $this->createUser(['two_factor_country_code' => null, 'two_factor_phone' => null]);
        $user2 = $this->createUser(['two_factor_country_code' => 1, 'two_factor_phone' => null]);
        $user3 = $this->createUser(['two_factor_country_code' => null, 'two_factor_phone' => '123456']);

        $this->actingAs($user1)->get("two-factor/verification")->assertResponseStatus(404);
        $this->actingAs($user2)->get("two-factor/verification")->assertResponseStatus(404);
        $this->actingAs($user3)->get("two-factor/verification")->assertResponseStatus(404);
    }

    /** @test */
    public function users_who_have_already_enabled_2fa_cannot_view_the_phone_verification_page()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        Authy::shouldReceive('isEnabled')->andReturn(true);

        $this->get("two-factor/verification")
            ->assertResponseStatus(404);
    }

    /** @test */
    public function users_who_have_already_enabled_2fa_cannot_submit_enable_2fa_form()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        Authy::shouldReceive('isEnabled')->andReturn(true);

        $this->post("two-factor/enable", ['country_code' => '1', 'phone_number' => '123'])
            ->assertResponseStatus(404);
    }

    /** @test */
    public function users_who_have_already_enabled_2fa_cannot_submit_verification_form()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        Authy::shouldReceive('isEnabled')->andReturn(true);

        $this->post("two-factor/verify")
            ->assertResponseStatus(404);
    }

    /** @test */
    public function token_field_is_required_during_2fa_phone_verification()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser(['two_factor_country_code' => "1", 'two_factor_phone' => "123123"]);

        $this->post("two-factor/verify")
            ->assertSessionHasErrors('token');
    }

    /** @test */
    public function the_2fa_verification_with_wrong_token_will_fail()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $user = $this->createAndLoginUser(['two_factor_country_code' => "1", 'two_factor_phone' => "123123"]);

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('tokenIsValid')->with($user, "123123")->andReturn(false);

        $this->visit("two-factor/verification")
            ->submitForm('Verify', ['token' => '123123'])
            ->seePageIs("two-factor/verification")
            ->see('Invalid 2FA Token');
    }

    /** @test */
    public function successful_2fa_phone_verification()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->expectsEvents(TwoFactorEnabled::class);

        $user = $this->createAndLoginUser(['two_factor_country_code' => "1", 'two_factor_phone' => "123123"]);

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('tokenIsValid')->with($user, '123123')->andReturn(true);

        $this->visit("two-factor/verification")
            ->submitForm('Verify', ['token' => '123123'])
            ->seePageIs("profile")
            ->seeInDatabase('users', [
                'id' => $user->id,
                'two_factor_options' => '{"enabled":true}'
            ]);
    }

    /** @test */
    public function successful_2fa_phone_verification_for_other_user()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->expectsEvents(TwoFactorEnabledByAdmin::class);

        $this->createAndLoginAdminUser();

        $user = factory(User::class)->create(['two_factor_country_code' => "1", 'two_factor_phone' => "123123"]);

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('tokenIsValid')->once()->andReturn(true);

        $this->visit("two-factor/verification?user={$user->id}")
            ->submitForm('Verify', ['token' => '123123'])
            ->seePageIs("/user/{$user->id}/edit")
            ->seeInDatabase('users', [
                'id' => $user->id,
                'two_factor_options' => '{"enabled":true}'
            ]);
    }

    /** @test */
    public function user_cannot_submit_phone_verification_form_if_phone_is_not_provided()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $user1 = $this->createUser(['two_factor_country_code' => null, 'two_factor_phone' => null]);
        $user2 = $this->createUser(['two_factor_country_code' => 1, 'two_factor_phone' => null]);
        $user3 = $this->createUser(['two_factor_country_code' => null, 'two_factor_phone' => '123456']);

        $this->actingAs($user1)->post("two-factor/verify")->assertResponseStatus(404);
        $this->actingAs($user2)->post("two-factor/verify")->assertResponseStatus(404);
        $this->actingAs($user3)->post("two-factor/verify")->assertResponseStatus(404);
    }

    /** @test */
    public function user_can_request_a_new_sms_with_a_code_once_per_minute()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser(['two_factor_country_code' => "1", 'two_factor_phone' => "123123"]);

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('sendTwoFactorVerificationToken')->once()->andReturn(false);

        $this->post("/two-factor/resend");
        $this->post("/two-factor/resend");
        $this->post("/two-factor/resend");
    }

    /** @test */
    public function only_user_with_appropriate_permissions_can_request_new_2fa_token_for_another_user()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        $user = factory(User::class)->create(['two_factor_country_code' => "1", 'two_factor_phone' => "123123"]);

        $this->post("/two-factor/resend", ['user' => $user->id])
            ->assertResponseStatus(403);
    }

    /** @test */
    public function user_can_request_a_new_sms_with_a_code_once_per_minute_while_enabling_2fa_for_other_user()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginAdminUser();

        $user = factory(User::class)->create(['two_factor_country_code' => "1", 'two_factor_phone' => "123123"]);

        $repo = \Mockery::mock(UserRepository::class);
        $repo->shouldReceive('find')->with($user->id)->andReturn($user);
        $this->app->instance(UserRepository::class, $repo);

        Authy::shouldReceive('isEnabled')->andReturn(false);
        Authy::shouldReceive('sendTwoFactorVerificationToken')->once()->with($user)->andReturn(false);

        $this->post("/two-factor/resend", ['user' => $user->id]);
        $this->post("/two-factor/resend", ['user' => $user->id]);
        $this->post("/two-factor/resend", ['user' => $user->id]);
    }

    /** @test */
    public function users_cannot_request_new_codes_if_they_already_have_2fa_enabled()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        Authy::shouldReceive('isEnabled')->andReturn(true);
        Authy::shouldReceive('sendTwoFactorVerificationToken')->never();

        $this->post("/two-factor/resend")->assertResponseStatus(404);
    }

    /** @test */
    public function user_cannot_hit_resend_endpoint_if_phone_is_not_provided()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $user1 = $this->createUser(['two_factor_country_code' => null, 'two_factor_phone' => null]);
        $user2 = $this->createUser(['two_factor_country_code' => 1, 'two_factor_phone' => null]);
        $user3 = $this->createUser(['two_factor_country_code' => null, 'two_factor_phone' => '123456']);

        $this->actingAs($user1)->post("/two-factor/resend")->assertResponseStatus(404);
        $this->actingAs($user2)->post("/two-factor/resend")->assertResponseStatus(404);
        $this->actingAs($user3)->post("/two-factor/resend")->assertResponseStatus(404);
    }

    /** @test */
    public function user_can_disable_2fa()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        $this->expectsEvents(\Tagydes\Events\User\TwoFactorDisabled::class);

        Authy::shouldReceive('isEnabled')->andReturn(true);
        Authy::shouldReceive('delete')->andReturnNull();

        $this->visit("profile")
            ->press('Disable')
            ->seePageIs("profile")
            ->see('Two-Factor Authentication disabled successfully.');
    }

    /** @test */
    public function user_can_disable_2fa_for_another_user()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->expectsEvents(\Tagydes\Events\User\TwoFactorDisabled::class);

        $this->createAndLoginAdminUser();

        $user = factory(User::class)->create();

        Authy::shouldReceive('isEnabled')->andReturn(true);
        Authy::shouldReceive('delete')->andReturnNull();

        $this->visit("/user/{$user->id}/edit")
            ->press('Disable')
            ->seePageIs("/user/{$user->id}/edit")
            ->seeInDatabase('users', [
                'id' => $user->id,
                'two_factor_country_code' => null,
                'two_factor_phone' => null
            ])
            ->see('Two-Factor Authentication disabled successfully.');
    }

    /** @test */
    public function user_without_appropriate_permissions_cannot_disable_2fa_for_another_user()
    {
        $this->setSettings(['2fa.enabled' => true]);

        $this->createAndLoginUser();

        $user = factory(User::class)->create();

        Authy::shouldReceive('isEnabled')->andReturn(true);

        $this->post("two-factor/disable", ['user' => $user->id])
            ->assertResponseStatus(403);
    }
}
