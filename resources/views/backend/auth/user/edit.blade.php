@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.edit'))

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')

{{ Form::model($user, ['route' => ['admin.auth.user.update', $user], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    @lang('labels.backend.access.users.management')
                    <small class="text-muted">@lang('labels.backend.access.users.edit')</small>
                </h4>
            </div>
            <!--col-->
        </div>
        <!--row-->

        <hr>

        <div class="row mt-4 mb-4">
            <div class="col">
                <div class="form-group row">
                    {{ Form::label('group_id', trans('validation.attributes.backend.access.users.group_id'), ['class' => 'col-md-2 from-control-label required']) }}

                    <div class="col-md-10">
                        {{ Form::select('group_id', $groups, null, ['class' => 'form-control categories box-size', 'data-placeholder' => trans('validation.attributes.backend.access.users.group_id'), 'required' => 'required']) }}
                    </div>
                    <!--col-->
                </div>

                <div class="form-group row">
                    {{ Form::label('forward_rate', trans('validation.attributes.backend.access.users.forward_rate'), ['class' => 'col-md-2 from-control-label required']) }}

                    <div class="col-md-10">
                        {{ Form::text('forward_rate', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.forward_rate'), 'required' => 'required']) }}
                    </div>
                    <!--col-->
                </div>

                <div class="form-group row">
                    {{ Form::label('first_name', __('validation.attributes.backend.access.users.first_name'), [ 'class'=>'col-md-2 form-control-label']) }}

                    <div class="col-md-10">
                        {{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.first_name'), 'required' => 'required']) }}
                    </div>
                    <!--col-->
                </div>
                <!--form-group-->

                <div class="form-group row">
                    {{ Form::label('last_name', __('validation.attributes.backend.access.users.last_name'), [ 'class'=>'col-md-2 form-control-label']) }}

                    <div class="col-md-10">
                        {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.last_name'), 'required' => 'required']) }}
                    </div>
                    <!--col-->
                </div>
                <!--form-group-->

                <div class="form-group row">
                    {{ Form::label('email', __('validation.attributes.backend.access.users.email'), [ 'class'=>'col-md-2 form-control-label']) }}

                    <div class="col-md-10">
                        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.email'), 'required' => 'required']) }}
                    </div>
                    <!--col-->
                </div>
                <!--form-group-->

                @if ($user->id != 1)

                <div class="form-group row">
                    {{ Form::label('status', trans('validation.attributes.backend.access.users.active'), ['class' => 'col-md-2 control-label']) }}
                    <div class="col-md-10">
                        {{ Form::checkbox('status', '1', $user->status == 1) }}
                    </div>
                </div>
                <!--form control-->

                <div class="form-group row">
                    {{ Form::label('confirmed', trans('validation.attributes.backend.access.users.confirmed'), ['class' => 'col-md-2 control-label']) }}
                    <div class="col-md-10">
                        {{ Form::checkbox('confirmed', '1', $user->confirmed == 1) }}
                    </div>
                </div>
                <!--form control-->

                <div class="form-group row">
                    {{ Form::label('status', trans('validation.attributes.backend.access.users.associated_roles'), ['class' => 'col-md-2 control-label']) }}

                    <div class="col-md-8">
                        @if (count($roles) > 0)
                        @foreach($roles as $role)
                        <label for="role-{{$role->id}}" class="control">
                            <input type="radio" value="{{$role->id}}" name="assignees_roles[]" {{ is_array(old('assignees_roles')) ? (in_array($role->id, old('assignees_roles')) ? 'checked' : '') : (in_array($role->id, $userRoles) ? 'checked' : '') }} id="role-{{$role->id}}" class="get-role-for-permissions" /> &nbsp;&nbsp;{!! $role->name !!}
                        </label>
                        <!--permission list-->
                        @endforeach
                        @else
                        {{ trans('labels.backend.access.users.no_roles') }}
                        @endif
                    </div>
                    <!--col-lg-3-->
                </div>
                <!--form control-->

                @endif
            </div>
            <!--col-->
        </div>
        <!--row-->
    </div>
    <!--card-body-->

    @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.auth.user.index', 'id' => $user->id ])
</div>
<!--card-->
@if ($user->id == 1)
{{ Form::hidden('status', 1) }}
{{ Form::hidden('confirmed', 1) }}
{{ Form::hidden('assignees_roles[]', 1) }}
@endif

{{ Form::close() }}
@endsection

@section('pagescript')
<script>
    FTX.Utils.documentReady(function() {
        FTX.Users.edit.selectors.getPremissionURL = "{{ route('admin.get.permission') }}";
        FTX.Users.edit.init();
    });
</script>
@endsection