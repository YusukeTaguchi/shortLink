<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css">

<div class="card-body">
    <div class="row">
        <div class="col-sm-5">
            <h4 class="card-title mb-0">
                {{ __('labels.backend.access.links.management') }}
                <small class="text-muted">{{ (isset($link)) ? __('labels.backend.access.links.edit') : __('labels.backend.access.links.create') }}</small>
            </h4>
        </div>
        <!--col-->
    </div>
    <!--row-->

    <hr>

    <div class="row mt-4 mb-4">
        <div class="col">
            <div class="form-group row">
                {{ Form::label('title', trans('validation.attributes.backend.access.links.title'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.links.title'), 'required' => 'required']) }}
                </div>
                <!--col-->
            </div>

            <div class="form-group row">
                {{ Form::label('fake', trans('validation.attributes.backend.access.links.fake'), ['class' => 'col-md-2 from-control-label required']) }}

                @php
                    $fake = isset($link) ? '' : 'checked'
                @endphp
                
                <div class="col-md-10">
                    <div class="checkbox d-flex align-items-center">
                        <label class="switch switch-label switch-pill switch-primary mr-2" for="role-1"><input class="switch-input" type="checkbox" name="fake" id="role-1" value="1" {{ (isset($link->fake) && $link->fake === 1) ? "checked" : $fake }}><span class="switch-slider" data-checked="on" data-unchecked="off"></span></label>
                    </div>
                </div>
                <!--col-->
            </div>
            <!--form-group-->
            
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('domain_id', trans('validation.attributes.backend.access.links.domains'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::select('domain_id', $domains, null, ['class' => 'form-control categories box-size', 'data-placeholder' => trans('validation.attributes.backend.access.links.domains'), 'required' => 'required']) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('slug', trans('validation.attributes.backend.access.links.slug'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::text('slug', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.links.slug'), 'disabled' => 'disabled']) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->
            @if ($logged_in_user->isAdmin() || $logged_in_user->isExecutive())
                <div class="form-group row">
                    {{ Form::label('original_link', trans('validation.attributes.backend.access.links.original_link'), ['class' => 'col-md-2 from-control-label required']) }}

                    <div class="col-md-10">
                        {{ Form::text('original_link', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.links.original_link')]) }}
                    </div>
                    <!--col-->
                </div>
                <!--form-group-->
            @else
                <div class="form-group row">
                    {{ Form::label('original_link', trans('validation.attributes.backend.access.links.original_link'), ['class' => 'col-md-2 from-control-label required']) }}

                    <div class="col-md-10">
                        {{ Form::text('original_link', null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
                    </div>
                    <!--col-->
                </div>
                <!--form-group-->
            @endif

            <div class="form-group row">
                {{ Form::label('keywords', trans('validation.attributes.backend.access.links.keywords'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::text('keywords', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.links.keywords')]) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('description', trans('validation.attributes.backend.access.links.description'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.links.description')]) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('thumbnail_image', trans('validation.attributes.backend.access.links.thumbnail_image'), ['class' => 'col-md-2 from-control-label required']) }}

                @if(!empty($link->thumbnail_image))
                <div class="col-lg-1">
                    <img src="{{ asset('storage/img/link/'.$link->thumbnail_image) }}" height="80" width="80">
                </div>
                <div class="col-lg-5">
                    {{ Form::file('thumbnail_image', ['id' => 'thumbnail_image']) }}
                </div>
                @else
                <div class="col-lg-5">
                    {{ Form::file('thumbnail_image', ['id' => 'thumbnail_image']) }}
                </div>
                @endif
            </div>

            <div class="form-group row">
                {{ Form::label('notes', trans('validation.attributes.backend.access.links.notes'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::textarea('notes', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.links.notes')]) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('status', trans('validation.attributes.backend.access.links.status'), ['class' => 'col-md-2 from-control-label required']) }}
 
                <div class="col-md-10">
                    {{ Form::select('status', $status, 1, ['class' => 'form-control select2 status box-size', 'placeholder' => trans('validation.attributes.backend.access.links.status'), 'required' => 'required']) }}
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
        FTX.Links.edit.init("{{ config('locale.languages.' . app()->getLocale())[1] }}");
    });
</script>
@stop

