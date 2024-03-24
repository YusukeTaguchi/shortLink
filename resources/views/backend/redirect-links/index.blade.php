@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.redirect-links.management'))

@section('breadcrumb-links')
@include('backend.redirect-links.includes.breadcrumb-links')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('labels.backend.access.redirect-links.management') }} <small class="text-muted">{{ __('labels.backend.access.redirect-links.active') }}</small>
                </h4>
            </div>
            <!--col-->
        </div>
        <!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="redirect-links-table" class="table" data-ajax_url="{{ route("admin.redirect-links.get") }}">
                        <thead>
                            <tr>
                                <th>{{ trans('labels.backend.access.redirect-links.table.domain') }}</th>
                                <th>{{ trans('labels.backend.access.redirect-links.table.url') }}</th>
                                <th>{{ trans('labels.backend.access.redirect-links.table.status') }}</th>
                                <th>{{ trans('labels.backend.access.redirect-links.table.createdat') }}</th>
                                <th>{{ trans('labels.general.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--col-->
        </div>
        <!--row-->

    </div>
    <!--card-body-->
</div>
<!--card-->
@endsection

@section('pagescript')
<script>
    FTX.Utils.documentReady(function() {
        FTX.RedirectLinks.list.init();
    });
</script>

@stop