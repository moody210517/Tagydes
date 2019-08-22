<?php

namespace Tagydes\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use Tagydes\Events\User\UpdatedByAdmin;
use Tagydes\Http\Controllers\Api\ApiController;
use Tagydes\Http\Requests\User\CreateUserRequest;
use Tagydes\Http\Requests\User\UpdateUserRequest;
use Tagydes\Http\Requests\User\UploadAvatarRawRequest;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Services\Upload\UserAvatarManager;
use Tagydes\Support\Enum\UserStatus;
use Tagydes\Transformers\UserTransformer;
use Tagydes\User;

/**
 * Class AvatarController
 * @package Tagydes\Http\Controllers\Api\Users
 */
class AvatarController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var UserAvatarManager
     */
    private $avatarManager;

    public function __construct(UserRepository $users, UserAvatarManager $avatarManager)
    {
        $this->middleware('auth');
        $this->middleware('permission:users.manage');

        $this->users = $users;
        $this->avatarManager = $avatarManager;
    }

    /**
     * @param User $user
     * @param UploadAvatarRawRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(User $user, UploadAvatarRawRequest $request)
    {
        $name = $this->avatarManager->uploadAndCropAvatar(
            $user,
            $request->file('file')
        );

        $user = $this->users->update(
            $user->id,
            ['avatar' => $name]
        );

        event(new UpdatedByAdmin($user));

        return $this->respondWithItem($user, new UserTransformer);
    }

    /**
     * Update user's avatar to external resource.
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateExternal(User $user, Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url'
        ]);

        $this->avatarManager->deleteAvatarIfUploaded($user);

        $user = $this->users->update(
            $user->id,
            ['avatar' => $request->url]
        );

        event(new UpdatedByAdmin($user));

        return $this->respondWithItem($user, new UserTransformer);
    }

    /**
     * Remove user's avatar and set it to null.
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $this->avatarManager->deleteAvatarIfUploaded($user);

        $user = $this->users->update(
            $user->id,
            ['avatar' => null]
        );

        event(new UpdatedByAdmin($user));

        return $this->respondWithItem($user, new UserTransformer);
    }
}
