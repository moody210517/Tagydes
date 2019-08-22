<?php

namespace Tagydes\Listeners\Registration;

use Tagydes\Events\User\Registered;
use Tagydes\Notifications\EmailConfirmation;
use Tagydes\Repositories\User\UserRepository;

class SendConfirmationEmail
{
    /**
     * @var UserRepository
     */
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (! settings('reg_email_confirmation')) {
            return;
        }

        $user = $event->getRegisteredUser();

        $token = str_random(60);
        $this->users->update($user->id, [
            'confirmation_token' => $token
        ]);

        $user->notify(new EmailConfirmation($token));
    }
}
