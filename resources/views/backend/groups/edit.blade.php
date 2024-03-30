@extends('backend.layouts.app')

@section('title', __('labels.backend.access.groups.management') . ' | ' . __('labels.backend.access.groups.edit'))

@section('breadcrumb-links')
    @include('backend.groups.includes.breadcrumb-links')
@endsection

@section('content')
{{ Form::model($group, ['route' => ['admin.groups.update', $group], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'create-permission', 'files' => true]) }}

    <div class="card">
        @include('backend.groups.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.groups.index', 'id' => $group->id ])
    </div><!--card-->
    {{ Form::close() }}
@endsection