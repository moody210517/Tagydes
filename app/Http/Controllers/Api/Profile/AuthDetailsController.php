<?php

namespace Tagydes\Http\Controllers\Api\Profile;

use Tagydes\Events\User\UpdatedProfileDetails;
use Tagydes\Http\Controllers\Api\ApiController;
use Tagydes\Http\Requests\User\UpdateProfileDetailsRequest;
use Tagydes\Http\Requests\User\UpdateProfileLoginDetailsRequest;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Transformers\UserTransformer;

/**
 * Class DetailsController
 * @package Tagydes\Http\Controllers\Api\Profile
 */
class AuthDetailsController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Updates user profile details.
     * @param UpdateProfileLoginDetailsRequest $request
     * @param UserRepository $users
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileLoginDetailsRequest $request, UserRepository $users)
    {
        $user = $request->user();

        $data = $request->only(['email', 'username', 'password']);

        $user = $users->update($user->id, $data);

        return $this->respondWithItem($user, new UserTransformer);
    }
}
