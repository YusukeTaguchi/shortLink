@extends('backend.layouts.app')

@section('title', __('labels.backend.access.redirect-links.management') . ' | ' . __('labels.backend.access.redirect-links.edit'))

@section('breadcrumb-links')
    @include('backend.redirect-links.includes.breadcrumb-links')
@endsection

@section('content')
{{ Form::model($redirectLink, ['route' => ['admin.redirect-links.update', $redirectLink], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.redirect-links.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.redirect-links.index', 'id' => $redirectLink->id ])
    </div><!--card-->
    {{ Form::close() }}
@endsection