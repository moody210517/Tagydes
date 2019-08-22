<?php

namespace Tagydes\Http\Controllers\Api;

use Tagydes\Repositories\Session\SessionRepository;
use Tagydes\Transformers\SessionTransformer;

/**
 * Class SessionsController
 * @package Tagydes\Http\Controllers\Api\Users
 */
class SessionsController extends ApiController
{
    /**
     * @var SessionRepository
     */
    private $sessions;

    public function __construct(SessionRepository $sessions)
    {
        $this->middleware('auth');
        $this->middleware('session.database');
        $this->sessions = $sessions;
    }

    /**
     * Get info about specified session.
     * @param $session
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($session)
    {
        $this->authorize('manage-session', $session);

        return $this->respondWithItem(
            $session,
            new SessionTransformer
        );
    }

    /**
     * Destroy specified session.
     * @param $session
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($session)
    {
        $this->authorize('manage-session', $session);

        $this->sessions->invalidateSession($session->id);

        return $this->respondWithSuccess();
    }
}
