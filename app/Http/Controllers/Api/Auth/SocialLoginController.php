<?php

namespace Tagydes\Http\Controllers\Api\Auth;

use Auth;
use Exception;
use Socialite;
use Tagydes\Events\User\LoggedIn;
use Tagydes\Http\Controllers\Api\ApiController;
use Tagydes\Http\Requests\Auth\Social\ApiAuthenticateRequest;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Services\Auth\Social\SocialManager;

class SocialLoginController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var SocialManager
     */
    private $socialManager;

    /**
     * @param UserRepository $users
     * @param SocialManager $socialManager
     */
    public function __construct(UserRepository $users, SocialManager $socialManager)
    {
        $this->users = $users;
        $this->socialManager = $socialManager;
    }

    public function index(ApiAuthenticateRequest $request)
    {
        try {
            $socialUser = Socialite::driver($request->network)->userFromToken($request->social_token);
        } catch (Exception $e) {
            return $this->errorInternalError("Could not connect to specified social network.");
        }

        $user = $this->users->findBySocialId(
            $request->network,
            $socialUser->getId()
        );

        if (! $user) {
            if (! settings('reg_enabled')) {
                return $this->errorForbidden("Only users who already created an account can log in.");
            }

            $user = $this->socialManager->associate($socialUser, $request->network);
        }

        if ($user->isBanned()) {
            return $this->errorForbidden("Your account is banned by administrators.");
        }

        $token = Auth::guard('api')->login($user);

        event(new LoggedIn);

        return $this->respondWithArray([
            'token' => $token
        ]);
    }
}
