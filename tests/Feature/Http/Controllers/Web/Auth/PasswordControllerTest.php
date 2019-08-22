<?php

namespace Tests\Feature\Http\Controllers\Web\Auth;

use DB;
use Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Mockery;
use Tests\Feature\FunctionalTestCase;
use Tagydes\User;

class PasswordControllerTest extends FunctionalTestCase
{
    public function test_send_password_reminder()
    {
        Notification::fake();

        $user = factory(User::class)->create(['email' => 'test@test.com']);

        $this->visit('password/remind')
            ->type('test@test.com', 'email')
            ->press('Reset Password')
            ->seePageIs('password/remind')
            ->see('Password reset email sent. Check your inbox (and spam) folder.');

        Notification::assertSentTo(
            $user,
            \Tagydes\Notifications\ResetPassword::class,
            function ($notification) {
                $mail = $notification->toMail()->toArray();

                $this->assertContains(trans('app.request_for_password_reset_made'), $mail['introLines']);
                $this->assertContains(trans('app.if_you_did_not_requested'), $mail['outroLines']);

                return true;
            }
        );
    }

    public function test_password_reminder_with_wrong_email()
    {
        $this->visit('password/remind')
            ->type('test@test.com', 'email')
            ->press('Reset Password')
            ->seePageIs('password/remind')
            ->see('The selected email is invalid.');
    }

    public function test_password_reset()
    {
        $this->setSettings(['forgot_password' => true]);

        $user = factory(User::class)->create(['email' => 'test@test.com']);

        $token = $this->createNewToken();

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => \Carbon\Carbon::now()
        ]);

        $this->resetPassword($token, $user->email);

        $this->seePageIs('login')
            ->see('Your password has been reset!');

        $user = $user->fresh();

        $this->assertTrue(Hash::check('123123', $user->password));
    }

    public function test_password_reset_with_expired_token()
    {
        $this->setSettings(['forgot_password' => true]);

        $user = factory(User::class)->create(['email' => 'test@test.com']);

        $token = $this->createNewToken();

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => \Carbon\Carbon::now()->subHours(2)
        ]);

        $this->resetPassword($token, $user->email);

        $this->seePageIs("password/reset/{$token}")
            ->see("This password reset token is invalid.");
    }

    public function test_password_reset_with_invalid_email()
    {
        $this->setSettings(['forgot_password' => true]);

        $user = factory(User::class)->create(['email' => 'test@test.com']);

        $token = $this->createNewToken();

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => \Carbon\Carbon::now()
        ]);

        $this->resetPassword($token, 'foo@bar.com');

        $this->seePageIs("password/reset/{$token}")
            ->see("We can't find a user with that e-mail address.");
    }

    /**
     * @param $token
     * @param $email
     */
    private function resetPassword($token, $email)
    {
        $this->visit("password/reset/{$token}")
            ->type($email, 'email')
            ->type('123123', 'password')
            ->type('123123', 'password_confirmation')
            ->press('Update Password');
    }

    /**
     * Mock the event dispatcher so all events are silenced and collected.
     * We will override it to allow model events though, since we want
     * user notifications to be sent, so we can test them.
     *
     * @return $this
     */
    protected function withoutEvents()
    {
        $mock = Mockery::mock('Illuminate\Contracts\Events\Dispatcher');

        $mock->shouldReceive('fire', 'dispatch', 'getCommandHandler')->andReturnUsing(function ($called) {
            $this->firedEvents[] = $called;
        });

        $mock->shouldReceive('until');

        $this->app->instance('events', $mock);

        return $this;
    }

    private function createNewToken()
    {
        $key = $this->app['config']['app.key'];

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        return hash_hmac('sha256', Str::random(40), $key);
    }
}
