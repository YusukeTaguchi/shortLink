@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.links.management'))

@section('breadcrumb-links')
@include('backend.links.includes.breadcrumb-links')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('labels.backend.access.links.management') }} <small class="text-muted">{{ __('labels.backend.access.links.active') }}</small>
                </h4>
            </div>
            <!--col-->
        </div>
        <!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="links-table" class="table" data-ajax_url="{{ route("admin.links.get") }}">
                        <thead> 
                            <tr>
                                <th>{{ trans('labels.backend.access.links.table.id') }}</th>
                                <th>{{ trans('labels.backend.access.links.table.title') }}</th>
                                <th>{{ trans('labels.backend.access.links.table.thumbnail_image') }}</th>
                                <th>{{ trans('labels.backend.access.links.table.fake') }}</th>
                                <th>{{ trans('labels.backend.access.links.table.short_url') }}</th>
                                <th>{{ trans('labels.backend.access.links.table.status') }}</th>
                                <th>{{ trans('labels.backend.access.links.table.total_viewed') }}</th>
                                <th>{{ trans('labels.backend.access.links.table.createdby') }}</th>
                                <th>{{ trans('labels.backend.access.links.table.createdat') }}</th>
                                <th>{{ trans('labels.general.actions') }}</th>
                            </tr>
                        </thead>

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
        FTX.Links.list.init();
    });
</script>
@stop