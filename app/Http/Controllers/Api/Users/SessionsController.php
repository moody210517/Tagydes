<?php

namespace Tagydes\Http\Controllers\Api\Users;

use Tagydes\Http\Controllers\Api\ApiController;
use Tagydes\Repositories\Session\SessionRepository;
use Tagydes\Transformers\SessionTransformer;
use Tagydes\User;

/**
 * Class SessionsController
 * @package Tagydes\Http\Controllers\Api\Users
 */
class SessionsController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:users.manage');
        $this->middleware('session.database');
    }

    /**
     * Get sessions for specified user.
     * @param User $user
     * @param SessionRepository $sessions
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user, SessionRepository $sessions)
    {
        return $this->respondWithCollection(
            $sessions->getUserSessions($user->id),
            new SessionTransformer
        );
    }
}
