<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-1">@lang('app.two_factor_authentication')</h5>

        <small class="text-muted d-block mb-4">
            @lang('app.enable_disable_2fa')
        </small>

        @if (! config('services.authy.key'))
            <div class="alert alert-info">
                @lang('app.in_order_to_enable_2fa')
                @lang('app.new_application_on') <a href="https://www.authy.com/" target="_blank"><strong>@lang('app.authy_website')</strong></a>,
                @lang('app.and_update_your') <code>AUTHY_KEY</code> @lang('app.environment_variable_inside') <code>.env</code> @lang('app.file').
            </div>
        @else
            @if (settings('2fa.enabled'))
                {!! Form::open(['route' => 'settings.auth.2fa.disable', 'id' => 'auth-2fa-settings-form']) !!}
                <button type="submit" class="btn btn-danger" data-toggle="loader" data-loading-text="@lang('app.disabling')">
                    @lang('app.disable')
                </button>
                {!! Form::close() !!}
            @else
                {!! Form::open(['route' => 'settings.auth.2fa.enable', 'id' => 'auth-2fa-settings-form']) !!}
                <button type="submit" class="btn btn-primary" data-toggle="loader" data-loading-text="@lang('app.enabling')">
                    @lang('app.enable')
                </button>
                {!! Form::close() !!}
            @endif
        @endif
    </div>
</div>