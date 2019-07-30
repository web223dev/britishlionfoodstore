<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//This file is to include setting panels
	global $current_user;
	add_action('admin_menu', "ced_pas_settingpage");
	/**
	 * This function is for creating setting menu.
	 * @name ced_pas_settingpage()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_pas_settingpage(){	
		add_menu_page('Product Auto Share Settings', 'Product Auto Share ', 'manage_options', 'share-product-settings', 'ced_pas_settings_func');
		add_submenu_page( 'share-product-settings', 'Product Auto Share Products', 'Shared Products', 'manage_options', 'pas_product', 'ced_pas_products' );
	}

	/**
	 * This function is for including setting template.
	 * @name ced_pas_settings_func()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_pas_settings_func(){
		require_once( 'view/setting_template.php' );
	}
	
	/**
	 * This function is for including setting bulkshare.
	 * @name ced_pas_products()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	 function ced_pas_products () {
	 	require_once( CED_PAS_PATH.'assets/class-loader.php' );
	 	$loader = new ced_pas_loader();
	 	$loader->loader();
	 	$header = add_query_arg( 
	 		array(
	 			'post_type' => 'product',
	 			'ced_pas' => 'true'
	 		), 
	 		admin_url( 'edit.php' ) 
	 	);
	 	wp_localize_script( 'task', 'replace', array( 'url' => $header ) );
	}
	