@extends('backend.layouts.app')

@section('title', __('labels.backend.access.settings.management') . ' | ' . __('labels.backend.access.settings.edit'))

@section('breadcrumb-links')
    @include('backend.settings.includes.breadcrumb-links')
@endsection

@section('content')
{{ Form::model($setting, ['route' => ['admin.settings.update', $setting], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.settings.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.settings.index', 'id' => $setting->id ])
    </div><!--card-->
    {{ Form::close() }}
@endsection