<?php

Breadcrumbs::for('admin.groups.index', function ($trail) {
    $trail->push(__('labels.backend.access.groups.management'), route('admin.groups.index'));
});

Breadcrumbs::for('admin.groups.create', function ($trail) {
    $trail->parent('admin.groups.index');
    $trail->push(__('labels.backend.access.groups.management'), route('admin.groups.create'));
});

Breadcrumbs::for('admin.groups.edit', function ($trail, $id) {
    $trail->parent('admin.groups.index');
    $trail->push(__('labels.backend.access.groups.management'), route('admin.groups.edit', $id));
});
