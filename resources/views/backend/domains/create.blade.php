@extends('backend.layouts.app')

@section('title', __('labels.backend.access.domains.management') . ' | ' . __('labels.backend.access.domains.create'))

@section('breadcrumb-links')
    @include('backend.domains.includes.breadcrumb-links')
@endsection

@section('content')
{{ Form::open(['route' => 'admin.domains.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.domains.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.domains.index' ])
    </div><!--card-->
    {{ Form::close() }}
@endsection