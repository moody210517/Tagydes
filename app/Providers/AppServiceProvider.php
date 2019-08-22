<?php

namespace Tagydes\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Tagydes\Repositories\Activity\ActivityRepository;
use Tagydes\Repositories\Activity\EloquentActivity;
use Tagydes\Repositories\Country\CountryRepository;
use Tagydes\Repositories\Country\EloquentCountry;
use Tagydes\Repositories\Customer\CustomerRepository;
use Tagydes\Repositories\Customer\EloquentCustomer;
use Tagydes\Repositories\Permission\EloquentPermission;
use Tagydes\Repositories\Permission\PermissionRepository;
use Tagydes\Repositories\Reseller\EloquentReseller;
use Tagydes\Repositories\Reseller\ResellerRepository;
use Tagydes\Repositories\Branch\EloquentBranch;
use Tagydes\Repositories\Branch\BranchRepository;
use Tagydes\Repositories\Role\EloquentRole;
use Tagydes\Repositories\Role\RoleRepository;
use Tagydes\Repositories\Session\DbSession;
use Tagydes\Repositories\Session\SessionRepository;
use Tagydes\Repositories\User\EloquentUser;
use Tagydes\Repositories\User\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(config('app.locale'));
        config(['app.name' => settings('app_name')]);
        \Illuminate\Database\Schema\Builder::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserRepository::class, EloquentUser::class);
        $this->app->singleton(ResellerRepository::class, EloquentReseller::class);
        $this->app->singleton(BranchRepository::class, EloquentBranch::class);
        $this->app->singleton(CustomerRepository::class, EloquentCustomer::class);
        $this->app->singleton(ActivityRepository::class, EloquentActivity::class);
        $this->app->singleton(RoleRepository::class, EloquentRole::class);
        $this->app->singleton(PermissionRepository::class, EloquentPermission::class);
        $this->app->singleton(SessionRepository::class, DbSession::class);
        $this->app->singleton(CountryRepository::class, EloquentCountry::class);

        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
