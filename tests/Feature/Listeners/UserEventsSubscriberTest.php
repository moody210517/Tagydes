<?php

namespace Tests\Feature\Listeners;

class UserEventsSubscriberTest extends BaseListenerTestCase
{
    protected $theUser;

    public function setUp()
    {
        parent::setUp();
        $this->theUser = factory(\Tagydes\User::class)->create();
    }

    public function test_onLogin()
    {
        event(new \Tagydes\Events\User\LoggedIn);
        $this->assertMessageLogged('Logged in.');
    }

    public function test_onLogout()
    {
        event(new \Tagydes\Events\User\LoggedOut());
        $this->assertMessageLogged('Logged out.');
    }

    public function test_onRegister()
    {
        $this->setSettings([
            'reg_enabled' => true,
            'reg_email_confirmation' => true,
        ]);

        $user = factory(\Tagydes\User::class)->create();

        event(new \Tagydes\Events\User\Registered($user));

        $this->assertMessageLogged('Created an account.', $user);
    }

    public function test_onAvatarChange()
    {
        event(new \Tagydes\Events\User\ChangedAvatar);
        $this->assertMessageLogged('Updated profile avatar.');
    }

    public function test_onProfileDetailsUpdate()
    {
        event(new \Tagydes\Events\User\UpdatedProfileDetails);
        $this->assertMessageLogged('Updated profile details.');
    }

    public function test_onDelete()
    {
        event(new \Tagydes\Events\User\Deleted($this->theUser));

        $message = sprintf(
            "Deleted user %s.",
            $this->theUser->present()->nameOrEmail
        );

        $this->assertMessageLogged($message);
    }

    public function test_onBan()
    {
        event(new \Tagydes\Events\User\Banned($this->theUser));

        $message = sprintf(
            "Banned user %s.",
            $this->theUser->present()->nameOrEmail
        );

        $this->assertMessageLogged($message);
    }

    public function test_onUpdateByAdmin()
    {
        event(new \Tagydes\Events\User\UpdatedByAdmin($this->theUser));

        $message = sprintf(
            "Updated profile details for %s.",
            $this->theUser->present()->nameOrEmail
        );

        $this->assertMessageLogged($message);
    }

    public function test_onCreate()
    {
        event(new \Tagydes\Events\User\Created($this->theUser));

        $message = sprintf(
            "Created an account for user %s.",
            $this->theUser->present()->nameOrEmail
        );

        $this->assertMessageLogged($message);
    }

    public function test_onSettingsUpdate()
    {
        event(new \Tagydes\Events\Settings\Updated);
        $this->assertMessageLogged('Updated website settings.');
    }

    public function test_onTwoFactorEnable()
    {
        event(new \Tagydes\Events\User\TwoFactorEnabled);
        $this->assertMessageLogged('Enabled Two-Factor Authentication.');
    }

    public function test_onTwoFactorDisable()
    {
        event(new \Tagydes\Events\User\TwoFactorDisabled);
        $this->assertMessageLogged('Disabled Two-Factor Authentication.');
    }

    public function test_onTwoFactorEnabledByAdmin()
    {
        event(new \Tagydes\Events\User\TwoFactorEnabledByAdmin($this->theUser));

        $message = sprintf(
            "Enabled Two-Factor Authentication for user %s.",
            $this->theUser->present()->nameOrEmail
        );

        $this->assertMessageLogged($message);
    }

    public function test_onTwoFactorDisabledByAdmin()
    {
        event(new \Tagydes\Events\User\TwoFactorDisabledByAdmin($this->theUser));

        $message = sprintf(
            "Disabled Two-Factor Authentication for user %s.",
            $this->theUser->present()->nameOrEmail
        );

        $this->assertMessageLogged($message);
    }

    public function test_onPasswordResetEmailRequest()
    {
        event(new \Tagydes\Events\User\RequestedPasswordResetEmail($this->user));
        $this->assertMessageLogged("Requested password reset email.");
    }

    public function test_onPasswordReset()
    {
        event(new \Tagydes\Events\User\ResetedPasswordViaEmail($this->user));
        $this->assertMessageLogged("Reseted password using \"Forgot Password\" option.");
    }

    public function test_onStartImpersonating()
    {
        $impersonated = factory(\Tagydes\User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        event(new \Lab404\Impersonate\Events\TakeImpersonation($this->user, $impersonated));

        $this->assertMessageLogged("Started impersonating user John Doe (ID: {$impersonated->id})");;
    }

    public function test_onStopImpersonating()
    {
        $impersonated = factory(\Tagydes\User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        event(new \Lab404\Impersonate\Events\LeaveImpersonation($this->user, $impersonated));

        $this->assertMessageLogged("Stopped impersonating user John Doe (ID: {$impersonated->id})");
    }
}
