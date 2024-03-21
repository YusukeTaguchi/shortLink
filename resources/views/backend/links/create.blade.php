@extends('backend.layouts.app')

@section('title', __('labels.backend.access.links.management') . ' | ' . __('labels.backend.access.links.create'))

@section('breadcrumb-links')
    @include('backend.links.includes.breadcrumb-links')
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.links.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.links.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.links.index' ])
    </div><!--card-->
    {{ Form::close() }}
@endsection