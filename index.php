<?php

//Prevent direct access
defined('PLUGINPATH') or exit('No direct script access allowed');

/*
Plugin Name: Promo Codes & Discount Module
Plugin URL: https://example.com
Description: Easily manage promotional codes, discounts, and targeted client segment exports in rise CRM. Boost sales and personalize campaigns with this powerful utility module.
Version: 1.0
Requires at least: 2.8
Author: Jules
Author URL: https://example.com
*/

register_installation_hook("PromoAndDiscount", function ($item_purchase_code) {
    //run necessary sql queries
    $db = db_connect('default');
    $db_prefix = get_db_prefix();
    $db->query("SET sql_mode = ''");

    $db->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "promo_codes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `discount_type` enum('fixed','percentage') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'fixed',
        `discount_value` decimal(15,2) NOT NULL,
        `usage_limit` int(11) NOT NULL DEFAULT '0',
        `usage_per_customer` int(11) NOT NULL DEFAULT '0',
        `min_sale_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
        `deleted` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

    $db->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "promo_code_usage` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `promo_code_id` int(11) NOT NULL,
        `customer_id` int(11) NOT NULL,
        `sale_item_id` int(11) NOT NULL,
        `sale_item_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `usage_date` datetime NOT NULL,
        `deleted` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
});

app_hooks()->add_filter('app_filter_admin_settings_menu', function($settings_menu) {
    $settings_menu["setup"][] = array("name" => "promo_codes", "url" => "promo_codes/settings");
    return $settings_menu;
});

app_hooks()->add_action('app_hook_invoice_edit_form', function($data) {
    $promo_code_controller = new \PromoAndDiscount\Controllers\Promo_codes();
    echo $promo_code_controller->apply_promo_code_to_invoice($data);
});

app_hooks()->add_action('app_hook_subscription_edit_form', function($data) {
    $promo_code_controller = new \PromoAndDiscount\Controllers\Promo_codes();
    echo $promo_code_controller->apply_promo_code_to_subscription($data);
});

app_hooks()->add_action('app_hook_proposal_edit_form', function($data) {
    $promo_code_controller = new \PromoAndDiscount\Controllers\Promo_codes();
    echo $promo_code_controller->apply_promo_code_to_proposal($data);
});

app_hooks()->add_action('app_hook_estimate_edit_form', function($data) {
    $promo_code_controller = new \PromoAndDiscount\Controllers\Promo_codes();
    echo $promo_code_controller->apply_promo_code_to_estimate($data);
});

app_hooks()->add_action('app_hook_delivery_note_edit_form', function($data) {
    $promo_code_controller = new \PromoAndDiscount\Controllers\Promo_codes();
    echo $promo_code_controller->apply_promo_code_to_delivery_note($data);
});

app_hooks()->add_action('app_hook_purchase_order_edit_form', function($data) {
    $promo_code_controller = new \PromoAndDiscount\Controllers\Promo_codes();
    echo $promo_code_controller->apply_promo_code_to_purchase_order($data);
});
