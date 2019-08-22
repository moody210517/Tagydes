<?php

namespace Tests\Feature\Http\Controllers\Web;

use Tests\Feature\FunctionalTestCase;
use Tagydes\User;

class ImpersonateUsersTest extends FunctionalTestCase
{
    /** @test */
    public function a_user_with_appropriate_permission_can_impersonate_other_users_from_a_user_list_page()
    {
        $user = $this->createAndLoginUser();

        $this->addPermissionForUser($user, 'users.manage');

        factory(User::class)->create();

        $this->visit('/user')->see("Impersonate");
    }

    /** @test */
    public function a_user_dont_see_impersonate_button_next_to_his_name_in_the_user_list()
    {
        $user = $this->createAndLoginUser();

        $this->addPermissionForUser($user, 'users.manage');

        $this->visit('/user')->dontSee("Impersonate");
    }

    /** @test */
    public function clicking_on_impersonate_button_will_impersonate_the_user()
    {
        $user1 = $this->createAndLoginUser();

        $this->addPermissionForUser($user1, 'users.manage');

        $user2 = factory(User::class)->create();

        $this->assertTrue(auth()->user()->is($user1));

        $this->visit('/user')
            ->click("Impersonate")
            ->seePageIs("/");

        $this->assertTrue(auth()->user()->is($user2));
    }

    /** @test */
    public function while_impersonating_user_can_stop_impersonating_by_clicking_on_the_header_button()
    {
        $user = $this->createAndLoginUser();

        $this->addPermissionForUser($user, 'users.manage');

        factory(User::class)->create();

        $this->visit('/user')
            ->click("Impersonate")
            ->see("Stop Impersonating");

        $this->click("Stop Impersonating");

        $this->assertTrue(auth()->user()->is($user));
    }

    /** @test */
    public function while_impersonating_user_cannot_impersonate_other_user_even_if_he_has_a_permission()
    {
        $user1 = $this->createAndLoginUser();
        $user2 = factory(User::class)->create();

        $this->addPermissionForUser($user1, 'users.manage');
        $this->addPermissionForUser($user2, 'users.manage');

        $this->visit("/user")
            ->click("Impersonate")
            ->visit("/user")
            ->dontSeeElement(".impersonate");
    }

    /** @test */
    public function user_can_be_impersonated_by_clicking_on_impersonate_link_on_user_profile_page()
    {
        $user1 = $this->createAndLoginUser();
        $user2 = factory(User::class)->create();

        $this->addPermissionForUser($user1, 'users.manage');

        $this->visit("/user/{$user2->id}/show")->click("Impersonate");

        $this->seePageIs("/");
        $this->assertTrue(auth()->user()->is($user2));
    }
}
