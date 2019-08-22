<!-- begin #header -->
<div id="header" class="header navbar-default">
    <!-- begin navbar-header -->
    <div class="navbar-header">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            
            @if(auth()->user()->resellers()->first())
                {{ auth()->user()->resellers()->first()->company_name }}
            @else
                <img src="{{ asset('assets/img/logo/logo-tagydes.png') }}" />
            @endif
        </a>
        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- end navbar-header -->
    
    <!-- begin header-nav -->
    <ul class="navbar-nav navbar-right">
        @if (app('impersonate')->isImpersonating())
            <li>
                <a href="{{ route('impersonate.leave') }}" class="text-danger hidden-md">
                    <i class="fas fa-user-secret"></i>
                    <span>@lang('app.stop_impersonating')</span>
                </a>
            </li>
        @endif
        <li>
            <form class="navbar-form">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Enter keyword" />
                    <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                <i class="fa fa-bell"></i>
                <span class="label">5</span>
            </a>
            <ul class="dropdown-menu media-list dropdown-menu-right">
                <li class="dropdown-header">NOTIFICATIONS (5)</li>
                <li class="media">
                    <a href="javascript:;">
                        <div class="media-left">
                            <i class="fa fa-bug media-object bg-silver-darker"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
                            <div class="text-muted f-s-11">3 minutes ago</div>
                        </div>
                    </a>
                </li>
                <li class="media">
                    <a href="javascript:;">
                        <div class="media-left">
                            <img src="{{asset('assets/img/user/user-1.jpg')}}" class="media-object" alt="" />
                            <i class="fab fa-facebook-messenger text-primary media-object-icon"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading">John Smith</h6>
                            <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                            <div class="text-muted f-s-11">25 minutes ago</div>
                        </div>
                    </a>
                </li>
                <li class="media">
                    <a href="javascript:;">
                        <div class="media-left">
                            <img src="{{asset('assets/img/user/user-2.jpg')}}" class="media-object" alt="" />
                            <i class="fab fa-facebook-messenger text-primary media-object-icon"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading">Olivia</h6>
                            <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                            <div class="text-muted f-s-11">35 minutes ago</div>
                        </div>
                    </a>
                </li>
                <li class="media">
                    <a href="javascript:;">
                        <div class="media-left">
                            <i class="fa fa-plus media-object bg-silver-darker"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading"> New User Registered</h6>
                            <div class="text-muted f-s-11">1 hour ago</div>
                        </div>
                    </a>
                </li>
                <li class="media">
                    <a href="javascript:;">
                        <div class="media-left">
                            <i class="fa fa-envelope media-object bg-silver-darker"></i>
                            <i class="fab fa-google text-warning media-object-icon f-s-14"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading"> New Email From John</h6>
                            <div class="text-muted f-s-11">2 hour ago</div>
                        </div>
                    </a>
                </li>
                <li class="dropdown-footer text-center">
                    <a href="javascript:;">View more</a>
                </li>
            </ul>
        </li>
        <li class="dropdown navbar-user">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"  href="{{ route('user.show', auth()->user()->present()->id) }}">
                <img  class="octagon img-responsive"
                width="40"
                src="{{ asset(auth()->user()->avatar) }}"
                alt="{{ auth()->user()->present()->name }}" /> 
                <span class="d-none d-md-inline">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</span> <b class="caret"></b>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile') }}" class="dropdown-item">@lang('app.my_profile')</a>
                <a href="javascript:;" class="dropdown-item"><span class="badge badge-danger pull-right">2</span> Inbox</a>
                <a href="javascript:;" class="dropdown-item">Calendar</a>
                <a href="javascript:;" class="dropdown-item">Setting</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('auth.logout') }}" class="dropdown-item">Log Out</a>
            </div>
        </li>
    </ul>
    <!-- end header navigation right -->
</div>
        <!-- end #header -->