@extends('layouts.app')

@section('page-title', $user->present()->nameOrEmail . ' - ' . trans('app.active_sessions'))

@section('page-heading')
    @lang('app.sessions') ({{ $user->present()->nameOrEmail }})
@stop

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


    <li class="breadcrumb-item active">
        @lang('app.sessions')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>@lang('app.ip_address')</th>
                        <th>@lang('app.device')</th>
                        <th>@lang('app.browser')</th>
                        <th>@lang('app.last_activity')</th>
                        <th class="text-center">@lang('app.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($sessions))
                        @foreach ($sessions as $session)
                            <tr>
                                <td>{{ $session->ip_address }}</td>
                                <td>
                                    {{ $session->device ?: trans('app.unknown') }} ({{ $session->platform ?: trans('app.unknown') }})
                                </td>
                                <td>{{ $session->browser ?: trans('app.unknown') }}</td>
                                <td>{{ $session->last_activity->format(config('app.date_time_format')) }}</td>
                                <td class="text-center">
                                    <a href="{{ isset($profile) ? route('profile.sessions.invalidate', $session->id) : route('user.sessions.invalidate', [$user->id, $session->id]) }}"
                                       class="btn btn-icon"
                                       title="@lang('app.invalidate_session')"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       data-method="DELETE"
                                       data-confirm-title="@lang('app.please_confirm')"
                                       data-confirm-text="@lang('app.are_you_sure_invalidate_session')"
                                       data-confirm-delete="@lang('app.yes_proceed')">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6"><em>@lang('app.no_records_found')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop
