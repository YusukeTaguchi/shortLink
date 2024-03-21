<?php

Breadcrumbs::for('admin.domains.index', function ($trail) {
    $trail->push(__('labels.backend.access.domains.management'), route('admin.domains.index'));
});

Breadcrumbs::for('admin.domains.create', function ($trail) {
    $trail->parent('admin.domains.index');
    $trail->push(__('labels.backend.access.domains.management'), route('admin.domains.create'));
});

Breadcrumbs::for('admin.domains.edit', function ($trail, $id) {
    $trail->parent('admin.domains.index');
    $trail->push(__('labels.backend.access.domains.management'), route('admin.domains.edit', $id));
});
