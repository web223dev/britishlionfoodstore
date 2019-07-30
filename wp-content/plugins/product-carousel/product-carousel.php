<?php
/*
Plugin Name: Product Carousel
Description: Product Carousel is a WordPress product carousel/slider plugin for WooCommerce.
Plugin URI: http://wpexpand.com/product/product-carousel-pro
Author: WPExpand
Author URI: http://wpexpand.com
Version: 1.1.1
*/

/**
 * Directory Constant
 */
define( 'WPE_PRODUCT_CAROUSEL_URL', plugins_url('/') . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'WPE_PRODUCT_CAROUSEL_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Include files
 */
require_once( WPE_PRODUCT_CAROUSEL_DIR . 'inc/functions.php' );

// Redirect after active
function wpe_product_carousel_active_redirect( $plugin ) {
	if ( $plugin == plugin_basename( __FILE__ ) ) {
		exit( wp_redirect( admin_url() ) );
	}
}
add_action( 'activated_plugin', 'wpe_product_carousel_active_redirect',  10, 2);

function wpe_product_carousel_activate() {
	add_option( 'wpe_pc_activation_redirect', true );
}
register_activation_hook( __FILE__, 'wpe_product_carousel_activate' );

function wpe_pc_redirect() {
	if ( get_option( 'wpe_pc_activation_redirect', false ) ) {
		delete_option( 'wpe_pc_activation_redirect' );
		if ( ! isset( $_GET['activate-multi'] ) ) {
			wp_redirect( "edit.php?post_type=wpe_product_carousel&page=wpe_support_page" );
		}
	}
}
add_action( 'admin_init', 'wpe_pc_redirect' );