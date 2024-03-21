@extends('backend.layouts.app')

@section('title', __('labels.backend.access.redirect-links.management') . ' | ' . __('labels.backend.access.redirect-links.create'))

@section('breadcrumb-links')
    @include('backend.redirect-links.includes.breadcrumb-links')
@endsection

@section('content')
{{ Form::open(['route' => 'admin.redirect-links.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.redirect-links.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.redirect-links.index' ])
    </div><!--card-->
    {{ Form::close() }}
@endsection