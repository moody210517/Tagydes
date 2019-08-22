<?php

namespace Tests\Feature\Http\Controllers\Api\Auth\Password;

use Illuminate\Support\Facades\Notification;
use Tests\Feature\ApiTestCase;
use Tagydes\User;

class RemindControllerTest extends ApiTestCase
{
    public function test_send_password_reminder()
    {
        Notification::fake();

        $user = factory(User::class)->create(['email' => 'test@test.com']);

        $this->postJson('api/password/remind', ['email' => 'test@test.com']);

        $this->assertResponseOk();

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
        $this->postJson('api/password/remind', ['email' => 'test@test.com']);

        $this->assertResponseStatus(422)
            ->seeJsonEquals([
                'email' => ['The selected email is invalid.']
            ]);
    }
}
