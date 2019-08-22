<div class="card">
    <div class="card-body">
        <h5 class="card-title">@lang('app.general')</h5>

        {!! Form::open(['route' => 'settings.auth.update', 'id' => 'registration-settings-form']) !!}

        <div class="form-group my-4">
            <div class="d-flex align-items-center">
                <div class="switch">
                    <input type="hidden" value="0" name="reg_enabled">

                    <input
                        type="checkbox" name="reg_enabled"
                        id="switch-reg-enabled"
                        class="switch" value="1"
                        {{ settings('reg_enabled') ? 'checked' : '' }}>

                    <label for="switch-reg-enabled"></label>
                </div>
                <div class="ml-3 d-flex flex-column">
                    <label class="mb-0">@lang('app.allow_registration')</label>
                </div>
            </div>
        </div>

        <div class="form-group my-4">
            <div class="d-flex align-items-center">
                <div class="switch">
                    <input type="hidden" value="0" name="tos">
                    {!! Form::checkbox('tos', 1, settings('tos'), ['class' => 'switch', 'id' => 'switch-tos']) !!}
                    <label for="switch-tos"></label>
                </div>
                <div class="ml-3 d-flex flex-column">
                    <label class="mb-0">@lang('app.terms_and_conditions')</label>
                    <small class="pt-0 text-muted">
                        @lang('app.the_user_has_to_confirm')
                    </small>
                </div>
            </div>
        </div>

        <div class="form-group my-4">
            <div class="d-flex align-items-center">
                <div class="switch">
                    <input type="hidden" value="0" name="reg_email_confirmation">
                    {!! Form::checkbox('reg_email_confirmation', 1, settings('reg_email_confirmation'), ['class' => 'switch', 'id' => 'switch-reg-email-confirm']) !!}
                    <label for="switch-reg-email-confirm"></label>
                </div>
                <div class="ml-3 d-flex flex-column">
                    <label class="mb-0">@lang('app.email_confirmation')</label>
                    <small class="text-muted">
                        @lang('app.require_email_confirmation')
                    </small>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            @lang('app.update_settings')
        </button>
        {!! Form::close() !!}
    </div>
</div>