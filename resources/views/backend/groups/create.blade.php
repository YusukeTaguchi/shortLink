@extends('backend.layouts.app')

@section('title', __('labels.backend.access.groups.management') . ' | ' . __('labels.backend.access.groups.create'))

@section('breadcrumb-links')
    @include('backend.groups.includes.breadcrumb-links')
@endsection

@section('content')
{{ Form::open(['route' => 'admin.groups.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.groups.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.groups.index' ])
    </div><!--card-->
    {{ Form::close() }}
@endsection