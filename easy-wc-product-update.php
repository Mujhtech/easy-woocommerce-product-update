<?php
/**
Plugin Name: Easy Woocommerce Product Update
Plugin URI:  https://wordpress.org
Description: Update woocommerce product details with one click installation.
Version:     1.0.0
Author:      Muhideen Mujeeb Adeoye
Author URI:  https://mujh.tech
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages/
Text Domain: easy-wc-product-update
WC requires at least: 3.0
WC tested up to: 4.2
 */
defined('ABSPATH') || die('Direct access is not allow');


register_activation_hook( __FILE__, 'moowpg_admin_notice_example_activation_hook' );
 

