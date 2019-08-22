@extends('layouts.app')

@section('page-title', $user->present()->nameOrEmail)
@section('page-heading', $user->present()->nameOrEmail)


@section('breadcrumbs')

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('user.list') }}">@lang('app.users')</a></li>
    <li class="breadcrumb-item">{{ $user->present()->nameOrEmail }}</li> 
    
</ol>

<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">@lang('app.users')</h1>

@stop


@section('content')

<div class="row">
    <div class="col-lg-5 col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    @lang('app.details')

                    <small class="float-right">
                        @canBeImpersonated($user)
                            <a href="{{ route('impersonate', $user) }}"
                               data-toggle="tooltip" data-placement="top" title="@lang('app.impersonate_user')">
                                @lang('app.impersonate')
                            </a>
                            <span class="text-muted">|</span>
                        @endCanBeImpersonated

                        <a href="{{ route('user.edit', $user->id) }}" class="edit"
                           data-toggle="tooltip" data-placement="top" title="@lang('app.edit_user')">
                            @lang('app.edit')
                        </a>
                    </small>
                </h5>

                <div class="d-flex align-items-center flex-column pt-3">
                    <div>
                        <img class="rounded-circle img-thumbnail img-responsive mb-4"
                             width="130"
                             height="130" src="{{ $user->present()->avatar }}">
                    </div>

                    @if ($name = $user->present()->name)
                        <h5>{{ $user->present()->name }}</h5>
                    @endif
                    <a href="mailto:{{ $user->email }}" class="text-muted font-weight-light mb-2">
                        {{ $user->email }}
                    </a>
                </div>

                <ul class="list-group list-group-flush mt-3">
                    @if ($user->phone)
                        <li class="list-group-item">
                            <strong>@lang('app.phone'):</strong>
                            <a href="telto:{{ $user->phone }}">{{ $user->phone }}</a>
                        </li>
                    @endif
                    <li class="list-group-item">
                        <strong>@lang('app.address'):</strong>
                        {{ $user->present()->fullAddress }}
                    </li>
                    <li class="list-group-item">
                        <strong>@lang('app.last_logged_in'):</strong>
                        {{ $user->present()->lastLogin }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-xl-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">

                    
                    @lang('app.latest_activity')
                    

                    @if (count($userActivities))
                        <small class="float-right">
                            <a href="{{ route('activity.user', $user->id) }}" class="edit"
                               data-toggle="tooltip" data-placement="top" title="@lang('app.complete_activity_log')">
                                @lang('app.view_all')
                            </a>
                        </small>
                    @endif
                </h5>


                @isset($branches)
                <h5>
                Branches
                </h5>
                @foreach($branches as $branch)
                {{ $branch->title }} 
                </br>
                @endforeach
                @endisset


                @if (count($userActivities))
                    <table class="table table-borderless table-striped">
                        <thead>
                        <tr>
                            <th>@lang('app.action')</th>
                            <th>@lang('app.date')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($userActivities as $activity)
                            <tr>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->created_at->format(config('app.date_time_format')) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted font-weight-light"><em>@lang('app.no_activity_from_this_user_yet')</em></p>
                @endif
            </div>
        </div>
    </div>
</div>
@stop