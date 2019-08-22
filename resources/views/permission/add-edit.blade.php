@extends('layouts.app')

@section('page-title', trans('app.permissions'))
@section('page-heading', $edit ? $permission->name : trans('app.create_new_permission'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('permission.index') }}">@lang('app.permissions')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ $edit ? trans('app.edit') : trans('app.create') }}
    </li>
@stop

@section('content')

@include('partials.messages')

@if ($edit)
    {!! Form::open(['route' => ['permission.update', $permission->id], 'method' => 'PUT', 'id' => 'permission-form']) !!}
@else
    {!! Form::open(['route' => 'permission.store', 'id' => 'permission-form']) !!}
@endif

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <h5 class="card-title">
                    @lang('app.permission_details')
                </h5>
                <p class="text-muted font-weight-light">
                    A general permission information.
                </p>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="name">@lang('app.name')</label>
                    <input type="text" class="form-control" id="name"
                           name="name" placeholder="@lang('app.permission_name')" value="{{ $edit ? $permission->name : old('name') }}">
                </div>
                <div class="form-group">
                    <label for="display_name">@lang('app.display_name')</label>
                    <input type="text" class="form-control" id="display_name"
                           name="display_name" placeholder="@lang('app.display_name')" value="{{ $edit ? $permission->display_name : old('display_name') }}">
                </div>
                <div class="form-group">
                    <label for="description">@lang('app.description')</label>
                    <textarea name="description" id="description" class="form-control">{{ $edit ? $permission->description : old('description') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">
            {{ $edit ? trans('app.update_permission') : trans('app.create_permission') }}
        </button>
    </div>
</div>

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Tagydes\Http\Requests\Permission\UpdatePermissionRequest', '#permission-form') !!}
    @else
        {!! JsValidator::formRequest('Tagydes\Http\Requests\Permission\CreatePermissionRequest', '#permission-form') !!}
    @endif
@stop