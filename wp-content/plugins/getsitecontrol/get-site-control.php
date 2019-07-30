<?php
/**
 * Plugin Name: Popups, Lead Forms, Surveys, Live Chats WordPress Plugin - GetSiteControl
 * Plugin URI: https://getsitecontrol.com/
 * Description: GetSiteControl is an easy-to-use set of engagement tools for your WordPress website.
 * Online surveys, live chats, contact forms, email lead forms, promo messages,
 * follow and share social media tools - all managed from a single dashboard.
 * Seven of the most important engagement tools in a single time-saving WordPress plugin.
 * Version: 2.3.0
 * Requires at least: 3.0.1
 * Tested up to: 5.0.3
 * Author: getsitecontrol
 * Author URI:  https://getsitecontrol.com/
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'GSC_URL' ) ) {
	define( 'GSC_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'GSC_PATH' ) ) {
	define( 'GSC_PATH', plugin_dir_path( __FILE__ ) );
}

require_once GSC_PATH . 'includes/get-site-control-wordpress.php';

GetSiteControlWordPress::init();
