@extends('layouts.app')

@section('page-title', trans('app.roles'))
@section('page-heading', $edit ? $role->name : trans('app.create_new_role'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('role.index') }}">@lang('app.roles')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ $edit ? trans('app.edit') : trans('app.create') }}
    </li>
@stop

@section('content')

@include('partials.messages')

@if ($edit)
    {!! Form::open(['route' => ['role.update', $role->id], 'method' => 'PUT', 'id' => 'role-form']) !!}
@else
    {!! Form::open(['route' => 'role.store', 'id' => 'role-form']) !!}
@endif

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <h5 class="card-title">
                    @lang('app.role_details_big')
                </h5>
                <p class="text-muted">
                    A general role information.
                </p>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="name">@lang('app.name')</label>
                    <input type="text" class="form-control" id="name"
                           name="name" placeholder="@lang('app.role_name')" value="{{ $edit ? $role->name : old('name') }}">
                </div>
                <div class="form-group">
                    <label for="display_name">@lang('app.display_name')</label>
                    <input type="text" class="form-control" id="display_name"
                           name="display_name" placeholder="@lang('app.display_name')" value="{{ $edit ? $role->display_name : old('display_name') }}">
                </div>
                <div class="form-group">
                    <label for="description">@lang('app.description')</label>
                    <textarea name="description" id="description" class="form-control">{{ $edit ? $role->description : old('description') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    {{ $edit ? trans('app.update_role') : trans('app.create_role') }}
</button>

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Tagydes\Http\Requests\Role\UpdateRoleRequest', '#role-form') !!}
    @else
        {!! JsValidator::formRequest('Tagydes\Http\Requests\Role\CreateRoleRequest', '#role-form') !!}
    @endif
@stop