<?php

namespace Tagydes\Http\Controllers\Web;

use Tagydes\Http\Controllers\Controller;
use Tagydes\Repositories\Activity\ActivityRepository;
use Tagydes\Repositories\Activity\EloquentActivity;
use Tagydes\User;
use Illuminate\Http\Request;

/**
 * Class ActivityController
 * @package Tagydes\Http\Controllers
 */
class ActivityController extends Controller
{
    /**
     * @var EloquentActivity
     */
    private $activities;

    /**
     * ActivityController constructor.
     * @param ActivityRepository $activities
     */
    public function __construct(ActivityRepository $activities)
    {
        $this->middleware('auth');
        $this->middleware('permission:users.activity');
        $this->activities = $activities;
    }

    /**
     * Displays the page with activities for all system users.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = 20;
        $adminView = true;

        $activities = $this->activities->paginateActivities($perPage, $request->get('search'));

        return view('activity.index', compact('activities', 'adminView'));
    }

    /**
     * Displays the activity log page for specific user.
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userActivity(User $user, Request $request)
    {
        $perPage = 20;
        $perPage = 20;
        $adminView = true;

        $activities = $this->activities->paginateActivitiesForUser(
            $user->id, $perPage, $request->get('search')
        );

        return view('activity.index', compact('activities', 'user', 'adminView'));
    }
}
