<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-1">
            Google reCAPTCHA
        </h5>

        <small class="text-muted d-block mb-4">
            @lang('app.enable_disable_captcha_during_registration')
        </small>

        @if (! (config('captcha.secret') && config('captcha.sitekey')))
            <div class="alert alert-info">
                @lang('app.to_utilize_recaptcha_please_get') <code>@lang('app.site_key')</code> and <code>@lang('app.secret_key')</code>
                @lang('app.from') <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank"><strong>@lang('app.recaptcha_website')</strong></a>,
                @lang('app.and_update_your') <code>RECAPTCHA_SITEKEY</code> @lang('app.and') <code>RECAPTCHA_SECRETKEY</code> @lang('app.environment_variables_inside') <code>.env</code> @lang('app.file').
            </div>
        @else
            @if (settings('registration.captcha.enabled'))
                {!! Form::open(['route' => 'settings.registration.captcha.disable', 'id' => 'captcha-settings-form']) !!}
                <button type="submit" class="btn btn-danger">
                    @lang('app.disable')
                </button>
                {!! Form::close() !!}
            @else
                {!! Form::open(['route' => 'settings.registration.captcha.enable', 'id' => 'captcha-settings-form']) !!}
                <button type="submit" class="btn btn-primary">
                    @lang('app.enable')
                </button>
                {!! Form::close() !!}
            @endif
        @endif
    </div>
</div>