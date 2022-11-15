<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push(__('menu.home'), route('dashboard'));
});

// Managers
Breadcrumbs::for('list_managers', function ($trail) {
    $trail->parent('home');
    $trail->push(__('menu.managers'), route('list_managers'));
});

Breadcrumbs::for('create_manager', function ($trail) {
    $trail->parent('list_managers');
    $trail->push(__('Create'), route('create_manager'));
});

Breadcrumbs::for('edit_manager', function ($trail, $manager) {
    $trail->parent('list_managers');
    $trail->push(__('Edit'), route('edit_manager', $manager->id));
});

Breadcrumbs::for('clients_manager', function ($trail, $manager) {
    $trail->parent('list_managers');
    $trail->push(__('Manager clients'), route('manager_list_clients', $manager->id));
});

// Clients
Breadcrumbs::for('list_clients', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Clients'), route('list_clients'));
});

Breadcrumbs::for('edit_client', function ($trail, $client) {
    $trail->parent('list_clients');
    $trail->push(__('Edit'), route('edit_client', $client->id));
});

// Catalog
Breadcrumbs::for('catalog', function ($trail) {
    $trail->parent('home');
    $trail->push(__('menu.catalog'), route('catalog'));
});

// Products
Breadcrumbs::for('product', function ($trail, $slug) {
    $trail->parent('catalog');
    $trail->push(__('menu.product'), route('show_product', $slug));
});

// Orders
Breadcrumbs::for('list_orders', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Orders'), route('list_orders'));
});

Breadcrumbs::for('show_order', function ($trail, $order) {
    $trail->parent('list_orders');
    $trail->push(__('Show order'), route('show_order', $order->id));
});

Breadcrumbs::for('edit_order', function ($trail, $order) {
    $trail->parent('list_orders');
    $trail->push(__('Edit'), route('edit_order', $order->id));
});
