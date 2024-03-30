<?php

Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard'));
});

Breadcrumbs::for('admin.dashboard.today', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard.today'));
});

Breadcrumbs::for('admin.dashboard.monthly', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard.monthly'));
});



require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
require __DIR__.'/blogs/blog.php';
require __DIR__.'/links/link.php';
require __DIR__.'/settings/setting.php';
require __DIR__.'/domains/domain.php';
require __DIR__.'/redirect-links/redirect-link.php';
require __DIR__.'/groups/group.php';
require __DIR__.'/blog-categories/blog-categories.php';
require __DIR__.'/blog-tags/blog-tags.php';
require __DIR__.'/pages/page.php';
require __DIR__.'/faqs/faq.php';
require __DIR__.'/email-templates/email-template.php';
require __DIR__.'/auth/permission.php';
