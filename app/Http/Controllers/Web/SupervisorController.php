<?php

namespace Tagydes\Http\Controllers\Web;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Tagydes\Customer;
use Tagydes\Events\User\Banned;
use Tagydes\Events\User\Deleted;
use Tagydes\Events\User\UpdatedByAdmin;
use Tagydes\Http\Controllers\Controller;
use Tagydes\Http\Requests\User\CreateUserRequest;
use Tagydes\Http\Requests\User\UpdateDetailsRequest;
use Tagydes\Http\Requests\User\UpdateLoginDetailsRequest;
use Tagydes\Lib\ResellerOrCustomer;
use Tagydes\Repositories\Activity\ActivityRepository;
use Tagydes\Repositories\Country\CountryRepository;
use Tagydes\Repositories\Role\RoleRepository;
use Tagydes\Repositories\Session\SessionRepository;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Reseller;
use Tagydes\Services\Upload\UserAvatarManager;
use Tagydes\Support\Enum\UserStatus;
use Tagydes\User;
use Tagydes\Role;
use DB;
use Tagydes\Repositories\Reseller\ResellerRepository;

/**
 * Class UsersController
 * @package Tagydes\Http\Controllers
 */
class SupervisorController extends Controller
{
    /**
     * @var UserRepository
     */
    private $users;
    private $resellers;

    /**
     * UsersController constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users, ResellerRepository $resellers)
    {
        $this->middleware('auth');
        $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        $this->middleware('permission:users.manage');
        $this->users = $users;
        $this->resellers = $resellers;
    }
        
    public function index()
    {
        $user = auth()->user();            
        // reseller section                
        $query = User::query();            
        $status = Input::get('status');
        $search = Input::get('search');
        $perPage = 20;
        if ($status) {
            $query->where('status', $status);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', "like", "%{$search}%");
            });
        }
          
        if($user->getRole() == 1){ // if admin
            $query->where('role_id', 5);
        }else if($user->getRole() == 2){
            $query->where('role_id', 5);
            $query->where('associated_id', $user->getAssociateId());            
        }else if($user->getRole() == 4){
            $query->where('role_id', -1);                 
        }

        $supervisors = $query->orderBy('id', 'desc')
        ->paginate($perPage);                 
        $statuses = ['' => trans('app.all')] + UserStatus::lists();        
        return view('supervisor.list', compact('supervisors', 'statuses' ));
    }


    /**
     * Displays user profile page.
     *
     * @param User $user
     * @param ActivityRepository $activities
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(User $user, ActivityRepository $activities)
    {
        $userActivities = $activities->getLatestActivitiesForUser($user->id, 10);

        return view('user.view', compact('user', 'userActivities'));
    }

    /**
     * Displays form for creating a new user.
     *
     * @param CountryRepository $countryRepository
     * @param RoleRepository $roleRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CountryRepository $countryRepository, RoleRepository $roleRepository)
    {
        $countries = $this->parseCountries($countryRepository);
        $user = auth()->user();
        //$roles = Role::where('order' , '>', $user->getRole())->pluck('name','id');
        $roles = Role::where('order', 5)->pluck('name','id');
        $statuses = UserStatus::lists();               
        return view('user.add', compact('countries', 'roles', 'statuses'));
    }

    /**
     * Parse countries into an array that also has a blank
     * item as first element, which will allow users to
     * leave the country field unpopulated.
     * @param CountryRepository $countryRepository
     * @return array
     */
    private function parseCountries(CountryRepository $countryRepository)
    {
        return [0 => 'Select a Country'] + $countryRepository->lists()->toArray();
    }

    /**
     * Stores new user into the database.
     *
     * @param CreateUserRequest $request
     * @return mixed
     */
    public function store(CreateUserRequest $request, $extraData = null)
    {
        // When user is created by administrator, we will set his
        // status to Active by default.
        $data = $request->all() + ['status' => UserStatus::ACTIVE]  ; //['status' => UserStatus::ACTIVE]  
        
        if (!empty($extraData)) $data += $extraData;

        if (! array_get($data, 'country_id')) {
            $data['country_id'] = null;
        }

        // Username should be updated only if it is provided.
        // So, if it is an empty string, then we just leave it as it is.
        if (trim($data['username']) == '') {
            $data['username'] = null;
        }

        $u = auth()->user();
        $data['created_by'] = $u->getId();    
        if($u->getRole() == 2 || $u->getRole() == 4){
            $data['associated_id'] = $u->getAssociateId();
        }
        
        $user = $this->users->create($data);

        var_dump($u->getId().":::".$user->id);

        if($u->getRole() == 2){
            // get reseller id from reseller table 
            $reseller_user = DB::table('reseller_user')->where('user_id', $u->getId())->get();
            if($reseller_user->first()){
               
                                
            }                                        
        }
                
        if (array_get($data, 'company_name')) {
            return $user;
        }

        return redirect()->route('user.list')
        ->withSuccess(trans('app.user_created'));
    }

    /**
     * Displays edit user form.
     *
     * @param User $user
     * @param CountryRepository $countryRepository
     * @param RoleRepository $roleRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user, CountryRepository $countryRepository, RoleRepository $roleRepository)
    {
        $edit = true;
        $countries = $this->parseCountries($countryRepository);
        $roles = $roleRepository->lists();
        $statuses = UserStatus::lists();
        $socialLogins = $this->users->getUserSocialLogins($user->id);

        return view(
            'user.edit',
            compact('edit', 'user', 'countries', 'socialLogins', 'roles', 'statuses')
        );
    }

    /**
     * Updates user details.
     *
     * @param User $user
     * @param UpdateDetailsRequest $request
     * @return mixed
     */
    public function updateDetails(User $user, UpdateDetailsRequest $request)
    {
        $data = $request->all();

        if (! array_get($data, 'country_id')) {
            $data['country_id'] = null;
        }

        $this->users->update($user->id, $data);
        $this->users->setRole($user->id, $request->role_id);

        event(new UpdatedByAdmin($user));

        // If user status was updated to "Banned",
        // fire the appropriate event.
        if ($this->userIsBanned($user, $request)) {
            event(new Banned($user));
        }

        return redirect()->back()
        ->withSuccess(trans('app.user_updated'));
    }

    /**
     * Check if user is banned during last update.
     *
     * @param User $user
     * @param Request $request
     * @return bool
     */
    private function userIsBanned(User $user, Request $request)
    {
        return $user->status != $request->status && $request->status == UserStatus::BANNED;
    }

    /**
     * Update user's avatar from uploaded image.
     *
     * @param User $user
     * @param UserAvatarManager $avatarManager
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateAvatar(User $user, UserAvatarManager $avatarManager, Request $request)
    {
        $this->validate($request, ['avatar' => 'image']);

        $name = $avatarManager->uploadAndCropAvatar(
            $user,
            $request->file('avatar'),
            $request->get('points')
        );

        if ($name) {
            $this->users->update($user->id, ['avatar' => $name]);

            event(new UpdatedByAdmin($user));

            return redirect()->route('user.edit', $user->id)
            ->withSuccess(trans('app.avatar_changed'));
        }

        return redirect()->route('user.edit', $user->id)
        ->withErrors(trans('app.avatar_not_changed'));
    }

    /**
     * Update user's avatar from some external source (Gravatar, Facebook, Twitter...)
     *
     * @param User $user
     * @param Request $request
     * @param UserAvatarManager $avatarManager
     * @return mixed
     */
    public function updateAvatarExternal(User $user, Request $request, UserAvatarManager $avatarManager)
    {
        $avatarManager->deleteAvatarIfUploaded($user);

        $this->users->update($user->id, ['avatar' => $request->get('url')]);

        event(new UpdatedByAdmin($user));

        return redirect()->route('user.edit', $user->id)
        ->withSuccess(trans('app.avatar_changed'));
    }

    /**
     * Update user's login details.
     *
     * @param User $user
     * @param UpdateLoginDetailsRequest $request
     * @return mixed
     */
    public function updateLoginDetails(User $user, UpdateLoginDetailsRequest $request)
    {
        $data = $request->all();

        if (trim($data['password']) == '') {
            unset($data['password']);
            unset($data['password_confirmation']);
        }

        $this->users->update($user->id, $data);

        event(new UpdatedByAdmin($user));

        return redirect()->route('user.edit', $user->id)
        ->withSuccess(trans('app.login_updated'));
    }

    /**
     * Removes the user from database.
     *
     * @param User $user
     * @return $this
     */
    public function delete(User $user)
    {
        if ($user->id == Auth::id()) {
            return redirect()->route('user.list')
            ->withErrors(trans('app.you_cannot_delete_yourself'));
        }

        $this->users->delete($user->id);

        event(new Deleted($user));
        
        return redirect()->route('user.list')
        ->withSuccess(trans('app.user_deleted'));
    }

    /**
     * Displays the list with all active sessions for selected user.
     *
     * @param User $user
     * @param SessionRepository $sessionRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sessions(User $user, SessionRepository $sessionRepository)
    {
        $adminView = true;
        $sessions = $sessionRepository->getUserSessions($user->id);

        return view('user.sessions', compact('sessions', 'user', 'adminView'));
    }

    /**
     * Invalidate specified session for selected user.
     *
     * @param User $user
     * @param $session
     * @param SessionRepository $sessionRepository
     * @return mixed
     */
    public function invalidateSession(User $user, $session, SessionRepository $sessionRepository)
    {
        $sessionRepository->invalidateSession($session->id);

        return redirect()->route('user.sessions', $user->id)
        ->withSuccess(trans('app.session_invalidated'));
    }
}
