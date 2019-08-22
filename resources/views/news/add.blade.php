@extends('layouts.app')

@section('page-title', trans('app.add_customer'))
@section('page-heading', trans('app.create_new_customer'))



@section('content')

@include('partials.messages')

{!! Form::open(['route' => 'news.create', 'files' => true, 'id' => 'customer-form']) !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        Details
                    </h5>
                    <p class="text-muted font-weight-light">
                        Add news information.
                    </p>
                </div>
                <div class="col-md-6">                
                    <div class="form-group">
                        <label for="postal_code">Title</label>
                        <input type="text" class="form-control" id="title"
                        name="title" placeholder="Title" value="">
                    </div>

                    <div class="form-group">
                        <label for="postal_code"> Description </label>
                        <textarea type="text" class="form-control" id="description" name="description" placeholder="@lang('app.postal_code')" value=""> </textarea>
                    </div>

                    <div class="form-group">
                        <label for="postal_code">Video (Youtube link) </label>
                        <input type="text" class="form-control" id="postal_code"
                        name="video" placeholder="Video link" value="">
                    </div>

                    <div class="form-group">
                        <label for="postal_code">Image</label>
                        <input type="file" class="form-control" id="postal_code"
                        name="image" placeholder="@lang('app.postal_code')" value="">
                    </div>
                

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        Create News
                    </button>
                </div>
            </div>

            
        </div>

        
        
    </div>

    
    
{!! Form::close() !!}

<br>
@stop

@section('scripts')
    {!! HTML::script('assets/js/as/profile.js') !!}
    {!! JsValidator::formRequest('Tagydes\Http\Requests\Customer\CreateCustomerRequest', '#customer-form') !!}
@stop