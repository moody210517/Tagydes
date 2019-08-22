@extends('layouts.app')

@section('page-title', trans('app.activity_log'))
@section('page-heading', isset($user) ? $user->present()->nameOrEmail : trans('app.activity_log'))

@section('breadcrumbs')
    @if (isset($user) && isset($adminView))
        <li class="breadcrumb-item">
            <a href="{{ route('activity.index') }}">@lang('app.activity_log')</a>
        </li>
        <li class="breadcrumb-item active">
            {{ $user->present()->nameOrEmail }}
        </li>
    @else
        <li class="breadcrumb-item active">
            @lang('app.activity_log')
        </li>
    @endif
@stop

@section('content')

    <div class="card">
    <div class="card-body">
        <form action="" method="GET" id="users-form" class="border-bottom-light mb-3">
            <div class="row justify-content-between mt-3 mb-4">
                <div class="col-lg-5 col-md-6">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Input::get('search') }}"
                               placeholder="@lang('app.search_for_action')">

                        <span class="input-group-append">
                            @if (Input::has('search') && Input::get('search') != '')
                                <a href="{{ isset($adminView) ? route('activity.index') : route('profile.activity') }}"
                                   class="btn btn-light d-flex align-items-center"
                                   role="button">
                                    <i class="fas fa-times text-muted"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit" id="search-activities-btn">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-borderless table-striped">
                <thead>
                    @if (isset($adminView))
                        <th class="min-width-150">@lang('app.user')</th>
                    @endif
                    <th>@lang('app.ip_address')</th>
                    <th class="min-width-200">@lang('app.message')</th>
                    <th class="min-width-200">@lang('app.log_time')</th>
                    <th class="text-center">@lang('app.more_info')</th>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            @if (isset($adminView))
                                <td>
                                    @if (isset($user))
                                        {{ $activity->user->present()->nameOrEmail }}
                                    @else
                                        <a href="{{ route('activity.user', $activity->user_id) }}"
                                           data-toggle="tooltip" title="@lang('app.view_activity_log')">
                                            {{ $activity->user->present()->nameOrEmail }}
                                        </a>
                                    @endif
                                </td>
                            @endif
                            <td>{{ $activity->ip_address }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->created_at->format(config('app.date_time_format')) }}</td>
                            <td class="text-center">
                                <a tabindex="0" role="button" class="btn btn-icon"
                                   data-trigger="focus"
                                   data-placement="left"
                                   data-toggle="popover"
                                   title="@lang('app.user_agent')"
                                   data-content="{{ $activity->user_agent }}">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $activities->render(null, ['tab'=>'']) !!}
@stop