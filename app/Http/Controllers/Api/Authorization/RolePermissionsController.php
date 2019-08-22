<?php

namespace Tagydes\Http\Controllers\Api\Authorization;

use Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tagydes\Events\Role\PermissionsUpdated;
use Tagydes\Http\Controllers\Api\ApiController;
use Tagydes\Http\Requests\Role\CreateRoleRequest;
use Tagydes\Http\Requests\Role\RemoveRoleRequest;
use Tagydes\Http\Requests\Role\UpdateRolePermissionsRequest;
use Tagydes\Http\Requests\Role\UpdateRoleRequest;
use Tagydes\Repositories\Role\RoleRepository;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Role;
use Tagydes\Transformers\PermissionTransformer;
use Tagydes\Transformers\RoleTransformer;

/**
 * Class RolePermissionsController
 * @package Tagydes\Http\Controllers\Api
 */
class RolePermissionsController extends ApiController
{
    /**
     * @var RoleRepository
     */
    private $roles;

    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
        $this->middleware('auth');
        $this->middleware('permission:permissions.manage');
    }

    public function show(Role $role)
    {
        return $this->respondWithCollection(
            $role->cachedPermissions(),
            new PermissionTransformer
        );
    }

    /**
     * Update specified role.
     * @param Role $role
     * @param UpdateRolePermissionsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Role $role, UpdateRolePermissionsRequest $request)
    {
        $this->roles->updatePermissions(
            $role->id,
            $request->permissions
        );

        event(new PermissionsUpdated);

        return $this->respondWithCollection(
            $role->cachedPermissions(),
            new PermissionTransformer
        );
    }
}
