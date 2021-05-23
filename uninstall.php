<?php

/*

    ========================
        UNINSTALL FUNCTIONS
    ========================
*/

if (! defined('WP_UNINSTALL_PLUGIN') ) {
	exit;
}

remove_action('ewcpu_product_update_event', 'ewcpu_uninstall');


function ewcpu_uninstall() {

    if (wp_next_scheduled ( 'ewcpu_product_update_event' )) {

    	wp_clear_scheduled_hook('ewcpu_product_update_event');

    }

}
