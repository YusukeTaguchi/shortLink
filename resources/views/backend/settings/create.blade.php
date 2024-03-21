@extends('backend.layouts.app')

@section('title', __('labels.backend.access.settings.management') . ' | ' . __('labels.backend.access.settings.create'))

@section('breadcrumb-links')
    @include('backend.settings.includes.breadcrumb-links')
@endsection

@section('content')
{{ Form::open(['route' => 'admin.settings.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.settings.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.settings.index' ])
    </div><!--card-->
    {{ Form::close() }}
@endsection