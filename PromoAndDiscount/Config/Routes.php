<?php

namespace PromoAndDiscount\Config;

$routes = \Config\Services::routes();

$routes->group('promo_codes', ['namespace' => 'PromoAndDiscount\Controllers'], function ($routes) {
    $routes->get('settings', 'Promo_codes::settings');
    $routes->get('modal_form', 'Promo_codes::modal_form');
    $routes->post('save', 'Promo_codes::save');
    $routes->get('list_data', 'Promo_codes::list_data');
    $routes->post('delete', 'Promo_codes::delete');
    $routes->post('apply_code_to_invoice', 'Promo_codes::apply_code_to_invoice');
    $routes->post('apply_code_to_subscription', 'Promo_codes::apply_code_to_subscription');
    $routes->post('apply_code_to_proposal', 'Promo_codes::apply_code_to_proposal');
    $routes->post('apply_code_to_estimate', 'Promo_codes::apply_code_to_estimate');
    $routes->post('apply_code_to_delivery_note', 'Promo_codes::apply_code_to_delivery_note');
    $routes->post('apply_code_to_purchase_order', 'Promo_codes::apply_code_to_purchase_order');
    $routes->post('export_customers', 'Promo_codes::export_customers');
    $routes->get('usage_history_list_data', 'Promo_codes::usage_history_list_data');
});
