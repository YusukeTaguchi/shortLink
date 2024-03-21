<?php

Breadcrumbs::for('admin.links.index', function ($trail) {
    $trail->push(__('labels.backend.access.links.management'), route('admin.links.index'));
});

Breadcrumbs::for('admin.links.create', function ($trail) {
    $trail->parent('admin.links.index');
    $trail->push(__('labels.backend.access.links.management'), route('admin.links.create'));
});

Breadcrumbs::for('admin.links.edit', function ($trail, $id) {
    $trail->parent('admin.links.index');
    $trail->push(__('labels.backend.access.links.management'), route('admin.links.edit', $id));
});
