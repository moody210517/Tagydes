@extends('layouts.app')

@section('page-title', trans('app.dashboard'))
@section('page-heading', trans('app.dashboard'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('app.dashboard')
    </li>
@stop

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('profile') }}" class="text-center no-decoration">
                    <div class="icon my-3">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <p class="lead mb-0">@lang('app.update_profile')</p>
                </a>
            </div>
        </div>
    </div>

    @if (config('session.driver') == 'database')
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('profile.sessions') }}" class="text-center  no-decoration">
                        <div class="icon my-3">
                            <i class="fa fa-list fa-2x"></i>
                        </div>
                        <p class="lead mb-0">@lang('app.my_sessions')</p>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('profile.activity') }}" class="text-center no-decoration">
                    <div class="icon my-3">
                        <i class="fas fa-server fa-2x"></i>
                    </div>
                    <p class="lead mb-0">@lang('app.activity_log')</p>
                </a>
            </div>
        </div>

    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('auth.logout') }}" class="text-center no-decoration">
                    <div class="icon my-3">
                        <i class="fas fa-sign-out-alt fa-2x"></i>
                    </div>
                    <p class="lead mb-0">@lang('app.logout')</p>
                </a>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="panel-heading"></div>
            <div class="card-body">
                <h5 class="card-title">
                    @lang('app.activity') (@lang('app.last_two_weeks'))
                </h5>

                <div class="pt-4 px-3">
                    <canvas id="myChart" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
    <script>
        var labels = {!! json_encode(array_keys($activities)) !!};
        var activities = {!! json_encode(array_values($activities)) !!};
        var trans = {
            chartLabel: "{{ trans('app.registration_history')  }}",
            action: "{{ trans('app.action_sm')  }}",
            actions: "{{ trans('app.actions_sm')  }}"
        };
    </script>
    {!! HTML::script('assets/js/chart.min.js') !!}
    {!! HTML::script('assets/js/as/dashboard-default.js') !!}
@stop