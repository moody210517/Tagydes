<?php

namespace Tagydes\Http\Controllers\Web;

use Auth;
use Carbon\Carbon;
use Tagydes\Http\Controllers\Controller;
use Tagydes\Repositories\Activity\ActivityRepository;
use Tagydes\Repositories\Reseller\ResellerRepository;
use Tagydes\Repositories\Customer\CustomerRepository;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Support\Enum\UserStatus;
use Tagydes\News;

class DashboardController extends Controller
{
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var ActivityRepository
     */
    private $activities;

    private $resellers;

    private $customers;

    /**
     * DashboardController constructor.
     * @param UserRepository $users
     * @param ActivityRepository $activities
     */
    public function __construct(UserRepository $users, ActivityRepository $activities, ResellerRepository $resellers, CustomerRepository $customers)
    {
        $this->middleware('auth');
        $this->users = $users;
        $this->activities = $activities;
        $this->resellers = $resellers;
        $this->customers = $customers;

    }

    /**
     * Displays dashboard based on user's role.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (Auth::user()->hasRole('Admin')) {
            return $this->adminDashboard();
        }

        return $this->defaultDashboard();
    }

    /**
     * Displays dashboard for admin users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function adminDashboard()
    {
        $usersPerMonth = $this->users->countOfNewUsersPerMonth(
            Carbon::now()->subYear()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );

        $stats = [
            'resellersCount' => $this->resellers->countMainOffice(),
            'customersCount' => $this->customers->countcustomers(),
            'subscriptionsCount' => 0,
            'aboutExpiredCount' => 0,
        ];

        

        $latestRegistrations = $this->users->latest(6);
        $news = News::all();
        return view('dashboard.admin', compact('stats', 'latestRegistrations', 'usersPerMonth' , 'news'));
    }

    /**
     * Displays default dashboard for non-admin users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function defaultDashboard()
    {
        $activities = $this->activities->userActivityForPeriod(
            Auth::user()->id,
            Carbon::now()->subWeeks(2),
            Carbon::now()
        )->toArray();

        return view('dashboard.default', compact('activities'));
    }
}
