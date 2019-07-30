<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fredericgilles.net/fg-joomla-to-wordpress/
 * @since             2.0.0
 * @package           FG_Joomla_to_WordPress_Premium
 *
 * @wordpress-plugin
 * Plugin Name:       FG Joomla to WordPress Premium
 * Plugin URI:        https://www.fredericgilles.net/fg-joomla-to-wordpress/
 * Description:       A plugin to migrate categories, posts, tags, images, medias, menus and users from Joomla to WordPress
 * Version:           3.33.0
 * Author:            Frédéric GILLES
 * Author URI:        https://www.fredericgilles.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fgj2wpp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FGJ2WPP_PLUGIN_VERSION', '3.33.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fg-joomla-to-wordpress-activator.php
 */
function activate_fg_joomla_to_wordpress_premium() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fg-joomla-to-wordpress-activator.php';
	FG_Joomla_to_WordPress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fg-joomla-to-wordpress-deactivator.php
 */
function deactivate_fg_joomla_to_wordpress_premium() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fg-joomla-to-wordpress-deactivator.php';
	FG_Joomla_to_WordPress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fg_joomla_to_wordpress_premium' );
register_deactivation_hook( __FILE__, 'deactivate_fg_joomla_to_wordpress_premium' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fg-joomla-to-wordpress-premium.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fg_joomla_to_wordpress_premium() {

	define('FGJ2WPP_LOADED', 1);

	$plugin = new FG_Joomla_to_WordPress_Premium();
	$plugin->run();

}
run_fg_joomla_to_wordpress_premium();
