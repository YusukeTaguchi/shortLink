<?php

Breadcrumbs::for('admin.redirect-links.index', function ($trail) {
    $trail->push(__('labels.backend.access.redirect-links.management'), route('admin.redirect-links.index'));
});

Breadcrumbs::for('admin.redirect-links.create', function ($trail) {
    $trail->parent('admin.redirect-links.index');
    $trail->push(__('labels.backend.access.redirect-links.management'), route('admin.redirect-links.create'));
});

Breadcrumbs::for('admin.redirect-links.edit', function ($trail, $id) {
    $trail->parent('admin.redirect-links.index');
    $trail->push(__('labels.backend.access.redirect-links.management'), route('admin.redirect-links.edit', $id));
});
