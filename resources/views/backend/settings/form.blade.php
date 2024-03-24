<div class="card-body">
    <div class="row">
        <div class="col-sm-5">
            <h4 class="card-title mb-0">
                {{ __('labels.backend.access.settings.management') }}
                <small class="text-muted">{{ __('labels.backend.access.settings.create') }}</small>
            </h4>
        </div>
        <!--col-->
    </div>
    <!--row-->

    <hr>

    <div class="row mt-4 mb-4">
        <div class="col">
            <div class="form-group row">
                {{ Form::label('auto_redirect_type', trans('validation.attributes.backend.access.settings.auto_redirect_type'), ['class' => 'col-md-2 from-control-label required']) }}

                @php
                    $auto_redirect_type = isset($setting) ? '' : 'checked'
                @endphp
                
                <div class="col-md-10">
                    <div class="checkbox d-flex align-items-center">
                        <label class="switch switch-label switch-pill switch-primary mr-2" for="role-1"><input class="switch-input" type="checkbox" name="auto_redirect_type" id="role-1" value="1" {{ (isset($setting->auto_redirect_type) && $setting->auto_redirect_type === 1) ? "checked" : $auto_redirect_type }}><span class="switch-slider" data-checked="on" data-unchecked="off"></span></label>
                    </div>
                </div>
                <!--col-->
            </div>
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('auto_redirect_to', trans('validation.attributes.backend.access.settings.auto_redirect_to'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::text('auto_redirect_to', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.settings.auto_redirect_to')]) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->
        </div>
        <!--col-->
    </div>
    <!--row-->
</div>
<!--card-body-->

@section('pagescript')
<script type="text/javascript">
    FTX.Utils.documentReady(function() {
        FTX.Settings.edit.init("{{ config('locale.languages.' . app()->getLocale())[1] }}");
    });
</script>
@stop