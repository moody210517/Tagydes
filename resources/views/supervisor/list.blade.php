@extends('layouts.app')

@section('page-title', trans('app.users'))
@section('page-heading', trans('app.users'))

@section('breadcrumbs')

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">@lang('app.users')</li>
</ol>

<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">@lang('app.users')</h1>

@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Input::get('search') }}"
                               placeholder="@lang('app.search_for_users')">

                            <span class="input-group-append">
                                @if (Input::has('search') && Input::get('search') != '')
                                    <a href="{{ route('supervisor.list') }}"
                                           class="btn btn-light d-flex align-items-center text-muted"
                                           role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif

                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>

                <div class="col-md-2 mt-2 mt-md-0">
                    {!! Form::select('status', $statuses, Input::get('status'), ['id' => 'status', 'class' => 'form-control input-solid']) !!}
                </div>
               
                <div class="col-md-6">
                    <a href="{{ route('supervisor.create') }}" class="btn btn-primary btn-rounded float-right">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('app.add_supervisor')
                    </a>
                </div>                                                       
            </div>
        </form>


        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>                    
                    <th class="align-middle">@lang('app.username')</th>
                    <th class="align-middle">@lang('app.full_name')</th>
                    <th class="align-middle">@lang('app.email')</th>
                    <th class="align-middle">@lang('app.registration_date')</th>
                    <th class="align-middle">@lang('app.status')</th>
                    <th class="text-center min-width-150">@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                    @if (count($supervisors))
                        @foreach ($supervisors as $user)
                            <tr>
                            <td class="align-middle"> {{ $user->username ?: trans('app.n_a') }} </td>
                            <td class="align-middle"> {{ $user->first_name . ' ' . $user->last_name }} </td>
                            <td class="align-middle"> {{ $user->email }} </td>
                            <td class="align-middle">{{ $user->created_at->format(config('app.date_format')) }}</td>
                            <td class="align-middle">
                                <span class="badge badge-lg badge-{{ $user->present()->labelClass }}">
                                    {{ trans("app.{$user->status}") }}
                                </span>
                            </td>
                            

                            <td class="text-center align-middle">
                                <div class="dropdown show d-inline-block">
                                    <a class="btn btn-icon"
                                    href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                        @if (config('session.driver') == 'database')
                                            <a href="{{ route('user.sessions', $user->id) }}" class="dropdown-item text-gray-500">
                                                <i class="fas fa-list mr-2"></i>
                                                @lang('app.user_sessions')
                                            </a>
                                        @endif
                                        <a href="{{ route('user.show', $user->id) }}" class="dropdown-item text-gray-500">
                                            <i class="fas fa-eye mr-2"></i>
                                            @lang('app.view_user')
                                        </a>
                                        @canBeImpersonated($user)
                                            <a href="{{ route('impersonate', $user->id) }}" class="dropdown-item text-gray-500 impersonate">
                                                <i class="fas fa-user-secret mr-2"></i>
                                                @lang('app.impersonate')
                                            </a>
                                        @endCanBeImpersonated
                                    </div>
                                </div>

                                <a href="{{ route('user.edit', $user->id) }}"
                                class="btn btn-icon edit"
                                title="@lang('app.edit_user')"
                                data-toggle="tooltip" data-placement="top">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ route('user.delete', $user->id) }}"
                                class="btn btn-icon"
                                title="@lang('app.delete_user')"
                                data-toggle="tooltip"
                                data-placement="top"
                                data-method="DELETE"
                                data-confirm-title="@lang('app.please_confirm')"
                                data-confirm-text="@lang('app.are_you_sure_delete_user')"
                                data-confirm-delete="@lang('app.yes_delete_him')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                            
                        
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $supervisors->render(null, ['tab'=>'']) !!}

@stop


@section('scripts')
    <script>
        $("#status").change(function () {
            $("#users-form").submit();
        });
    </script>
@stop
