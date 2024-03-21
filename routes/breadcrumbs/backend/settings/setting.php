<?php

Breadcrumbs::for('admin.settings.index', function ($trail) {
    $trail->push(__('labels.backend.access.settings.management'), route('admin.settings.index'));
});

Breadcrumbs::for('admin.settings.create', function ($trail) {
    $trail->parent('admin.settings.index');
    $trail->push(__('labels.backend.access.settings.management'), route('admin.settings.create'));
});

Breadcrumbs::for('admin.settings.edit', function ($trail, $id) {
    $trail->parent('admin.settings.index');
    $trail->push(__('labels.backend.access.settings.management'), route('admin.settings.edit', $id));
});
