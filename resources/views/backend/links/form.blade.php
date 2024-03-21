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
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('slug', trans('validation.attributes.backend.access.links.slug'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::text('slug', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.links.slug'), 'disabled' => 'disabled']) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->

            <div class="form-group row">
                {{ Form::label('original_link', trans('validation.attributes.backend.access.links.original_link'), ['class' => 'col-md-2 from-control-label required']) }}

                <div class="col-md-10">
                    {{ Form::text('original_link', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.links.original_link')]) }}
                </div>
                <!--col-->
            </div>
            <!--form-group-->

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
            <!--form-group-->

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
                    {{ Form::select('status', $status, null, ['class' => 'form-control select2 status box-size', 'placeholder' => trans('validation.attributes.backend.access.links.status'), 'required' => 'required']) }}
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