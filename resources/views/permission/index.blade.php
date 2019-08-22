@extends('layouts.app')

@section('page-title', trans('app.permissions'))
@section('page-heading', trans('app.permissions'))

@section ('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('app.permissions')
    </li>
@stop

@section('content')

@include('partials.messages')

{!! Form::open(['route' => 'permission.save', 'class' => 'mb-4']) !!}

<div class="card">
    <div class="card-body">

        <div class="row mb-3 pb-3 border-bottom-light">
            <div class="col-lg-12">
                <div class="float-right">
                    <a href="{{ route('permission.create') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('app.add_permission')
                    </a>
                </div>
            </div>
        </div>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th class="min-width-200">@lang('app.name')</th>
                        @foreach ($roles as $role)
                            <th class="text-center">{{ $role->display_name }}</th>
                        @endforeach
                        <th class="text-center min-width-100">@lang('app.action')</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($permissions))
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->display_name ?: $permission->name }}</td>

                            @foreach ($roles as $role)
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        {!!
                                            Form::checkbox(
                                                "roles[{$role->id}][]",
                                                $permission->id,
                                                $role->hasPermission($permission->name),
                                                [
                                                    'class' => 'custom-control-input',
                                                    'id' => "cb-{$role->id}-{$permission->id}"
                                                ]
                                            )
                                        !!}
                                        <label class="custom-control-label d-inline"
                                               for="cb-{{ $role->id }}-{{ $permission->id }}"></label>
                                    </div>
                                </td>
                            @endforeach

                            <td class="text-center">
                            
                                <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-icon"
                                   title="@lang('app.edit_permission')" data-toggle="tooltip" data-placement="top">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if ($permission->removable)
                                    <a href="{{ route('permission.destroy', $permission->id) }}" class="btn btn-icon"
                                       title="@lang('app.delete_permission')"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       data-method="DELETE"
                                       data-confirm-title="@lang('app.please_confirm')"
                                       data-confirm-text="@lang('app.are_you_sure_delete_permission')"
                                       data-confirm-delete="@lang('app.yes_delete_it')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4"><em>@lang('app.no_records_found')</em></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@if (count($permissions))
    <div class="row">
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">@lang('app.save_permissions')</button>
        </div>
    </div>
@endif

{!! Form::close() !!}

@stop
