@extends('backend.layouts.app')

@section('title', __('labels.backend.access.links.management') . ' | ' . __('labels.backend.access.links.edit'))

@section('breadcrumb-links')
    @include('backend.links.includes.breadcrumb-links')
@endsection

@section('content')
    {{ Form::model($link, ['route' => ['admin.links.update', $link], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'edit-role', 'files' => true]) }}

    <div class="card">
        @include('backend.links.form')
        @include('backend.components.footer-buttons', [ 'cancelRoute' => 'admin.links.index', 'id' => $link->id ])
    </div><!--card-->
    {{ Form::close() }}
@endsection