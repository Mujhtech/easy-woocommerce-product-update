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

//define api url
define("API_URL", "https://mujh.tech");


register_activation_hook( __FILE__, 'ewcpu_admin_notice_example_activation_hook' );

register_activation_hook(__FILE__, 'ewcpu_event_activation');

register_deactivation_hook( __FILE__, 'ewcpu_event_deactivation' );

add_action('ewcpu_product_update_event', 'ewcpu_do_this_daily');
 

function ewcpu_admin_notice_example_activation_hook() {

    set_transient( 'ewcpu-admin-notice-example', true, 5 );

}

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	add_action( 'admin_notices', 'ewcpu_admin_error_notice' );

	return;

} else {

	add_action( 'admin_notices', 'ewcpu_admin_success_notice' );

}

function ewcpu_admin_success_notice() { 

	if( get_transient( 'ewcpu-admin-notice-example' ) ){
	?>

        <div class="updated notice is-dismissible">
            <p>Thank you for using this plugin! <strong>You are awesome</strong>.</p>
        </div>

<?php
		delete_transient( 'ewcpu-admin-notice-example' );
	}
}

function ewcpu_admin_error_notice() { ?>

        <div class="error">
            <p>
                WooCommerce plugin is not activated. Please install and activate it to use <strong>
                	Easy Woocommerce Product Update
                </strong>
               
            </p>
        </div>

<?php
}
 
function ewcpu_event_activation() {

    if (! wp_next_scheduled ( 'ewcpu_product_update_event' )) {

    	wp_schedule_event(time(), 'daily', 'ewcpu_product_update_event');

    }

}

function ewcpu_event_deactivation() {

    wp_clear_scheduled_hook( 'ewcpu_product_update_event' );

}
 
function ewcpu_do_this_daily() {


	$headers = array(
        'Content-Type'  => 'application/json',
    );

    $args = array(
    	'headers' => $headers,
        'timeout' => 60,
    );

    $request = wp_remote_get( API_URL, $args );

    if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {

        $response = json_decode( wp_remote_retrieve_body( $request ), true );

	    if ( 'success' == $response['data']['status'] ) {


	    	foreach ($response as $key => $value) {

	    		if () {

	    			$pid = wc_get_product_id_by_sku( $sku );
	    			$product = wc_get_product( $pid );
					$stock_quantity = $product->get_stock_quantity();
					$stock_status   = $product->get_stock_status();
					$product->set_stock_quantity();
					$product->set_stock_status('outofstock');
					$product->save();

		    	} else {

		    		$post_id = wp_insert_post( array(
		                'post_title' => $title,
		                'post_type' => 'product',
		                'post_status' => 'publish',
		                'post_content' => $body,
		            ));

		            $product = wc_get_product( $post_id );
		            $product->set_sku( $sku );
		            $product->set_status("publish");
					$product->set_catalog_visibility('visible');
					$product->set_description("Product Description");
					$product->set_price(10.55);
					$product->set_regular_price(10.55);
					$product->set_manage_stock(true);
					$product->set_stock_quantity(10);
					$product->set_stock_status('instock');
					$product->set_backorders('no');
					$product->set_reviews_allowed(true);
					$product->set_sold_individually(false);
					$product->set_category_ids(array(1,2,3));
		            $product->save();

		    	}

	    		
	    	}

	    } else {

	          

	    }

	}
}