@extends('backend.layouts.app')

@section('title', __('labels.backend.access.domains.management') . ' | ' . __('labels.backend.access.domains.edit'))

@section('breadcrumb-links')
    @include('backend.domains.includes.breadcrumb-links')
@endsection

@section('content')
{{ Form::model($domain, ['route' => ['admin.domains.update', $domain], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.domains.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.domains.index', 'id' => $domain->id ])
    </div><!--card-->
    {{ Form::close() }}
@endsection