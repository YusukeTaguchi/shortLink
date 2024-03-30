<div class="card-body">
    <div class="row">
        <div class="col-sm-5">
            <h4 class="card-title mb-0">
                {{ __('labels.backend.access.redirect-links.management') }}
                <small class="text-muted">{{ __('labels.backend.access.redirect-links.create') }}</small>
            </h4>
        </div>
        <!--col-->
    </div>
    <!--row-->

    <hr> 

    <div class="row mt-4 mb-4">
        <div class="col">
            <div class="form-group row">
                {{ Form::label('group_id', trans('validation.attributes.backend.access.links.group_id'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::select('group_id', $groups, null, ['class' => 'form-control categories box-size', 'data-placeholder' => trans('validation.attributes.backend.access.links.group_id'), 'required' => 'required']) }}
                </div>
                <!--col-->
            </div>

            <div class="form-group row">
                {{ Form::label('domain', trans('validation.attributes.backend.access.redirect-links.domain'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::text('domain', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.redirect-links.domain'), 'required' => 'required']) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('url', trans('validation.attributes.backend.access.redirect-links.url'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::text('url', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.redirect-links.url')]) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('status', trans('validation.attributes.backend.access.redirect-links.status'), ['class' => 'col-md-2 from-control-label required']) }}

                @php
                $status = isset($redirectLink) ? '' : 'checked'
                @endphp
                
                <div class="col-md-10">
                    <div class="checkbox d-flex align-items-center">
                        <label class="switch switch-label switch-pill switch-primary mr-2" for="role-1"><input class="switch-input" type="checkbox" name="status" id="role-1" value="1" {{ (isset($redirectLink->status) && $redirectLink->status === 1) ? "checked" : $status }}><span class="switch-slider" data-checked="on" data-unchecked="off"></span></label>
                    </div>
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
        FTX.RedirectLinks.edit.init("{{ config('locale.languages.' . app()->getLocale())[1] }}");
    });
</script>
@stop