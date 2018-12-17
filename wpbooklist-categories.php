<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: WPBookList Categories Extension
Plugin URI: https://www.jakerevans.com
Description: A WPBookList Extension for displaying Libraries in a Categorical Drop-Down Fashion
Version: 1.0.0
Text Domain: wpbooklist
Author: Jake Evans - Forward Creation
Author URI: https://www.jakerevans.com
License: GPL2
*/ 



global $wpdb;
require_once('includes/categories-functions.php');
require_once('includes/categories-ajaxfunctions.php');

// Root plugin folder directory.
if ( ! defined('WPBOOKLIST_VERSION_NUM' ) ) {
	define( 'WPBOOKLIST_VERSION_NUM', '6.1.2' );
}

// This Extension's Version Number.
define( 'WPBOOKLIST_CATEGORIES_VERSION_NUM', '6.1.2' );

// Root plugin folder URL of this extension
define('CATEGORIES_ROOT_URL', plugins_url().'/wpbooklist-categories/');

// Grabbing database prefix
define('CATEGORIES_PREFIX', $wpdb->prefix);

// Root plugin folder directory for this extension
define('CATEGORIES_ROOT_DIR', plugin_dir_path(__FILE__));

// Root WordPress Plugin Directory.
define( 'CATEGORIES_ROOT_WP_PLUGINS_DIR', str_replace( '/wpbooklist-categories', '', plugin_dir_path( __FILE__ ) ) );

// Root WPBL Dir.
if ( ! defined('ROOT_WPBL_DIR' ) ) {
	define( 'ROOT_WPBL_DIR', CATEGORIES_ROOT_WP_PLUGINS_DIR . 'wpbooklist/' );
}

// Root WPBL Url.
if ( ! defined('ROOT_WPBL_URL' ) ) {
	define( 'ROOT_WPBL_URL', plugins_url() . '/wpbooklist/' );
}

// Root WPBL Classes Dir.
if ( ! defined('ROOT_WPBL_CLASSES_DIR' ) ) {
	define( 'ROOT_WPBL_CLASSES_DIR', ROOT_WPBL_DIR . 'includes/classes/' );
}

// Root WPBL Transients Dir.
if ( ! defined('ROOT_WPBL_TRANSIENTS_DIR' ) ) {
	define( 'ROOT_WPBL_TRANSIENTS_DIR', ROOT_WPBL_CLASSES_DIR . 'transients/' );
}

// Root WPBL Translations Dir.
if ( ! defined('ROOT_WPBL_TRANSLATIONS_DIR' ) ) {
	define( 'ROOT_WPBL_TRANSLATIONS_DIR', ROOT_WPBL_CLASSES_DIR . 'translations/' );
}

// Root WPBL Root Img Icons Dir.
if ( ! defined('ROOT_WPBL_IMG_ICONS_URL' ) ) {
	define( 'ROOT_WPBL_IMG_ICONS_URL', ROOT_WPBL_URL . 'assets/img/icons/' );
}

// Root WPBL Root Utilities Dir.
if ( ! defined('ROOT_WPBL_UTILITIES_DIR' ) ) {
	define( 'ROOT_WPBL_UTILITIES_DIR', ROOT_WPBL_CLASSES_DIR . 'utilities/' );
}

// Root CSS URL for this extension
define('CATEGORIES_ROOT_CSS_URL', CATEGORIES_ROOT_URL.'assets/css/');

// Root JS URL for this extension
define('CATEGORIES_ROOT_JS_URL', CATEGORIES_ROOT_URL.'assets/js/');

// Root IMG URL for this extension
define('CATEGORIES_ROOT_IMG_URL', CATEGORIES_ROOT_URL.'assets/img/');

// Root UI DIR for this extension
define('CATEGORIES_ROOT_INCLUDES', CATEGORIES_ROOT_DIR.'includes/');

// Root UI DIR for this extension
define('CATEGORIES_ROOT_INCLUDES_UI', CATEGORIES_ROOT_DIR.'includes/ui/');

// Root Classes Directory for this extension
define('CATEGORIES_ROOT_CLASS_DIR', CATEGORIES_ROOT_DIR.'includes/classes/');

// Root Image Icons URL of this extension
define('CATEGORIES_ROOT_IMG_ICONS_URL', CATEGORIES_ROOT_URL.'assets/img/');

// Registers table names
add_action( 'init', 'wpbooklist_categories_register_table_name', 1 );

// Creates tables upon activation
register_activation_hook( __FILE__, 'wpbooklist_categories_create_tables' );

// For expanding the drop-downs
add_action( 'wp_footer', 'categories_expand_javascript' );

// For replacing shortcode in the content if on mobile
add_filter( 'the_content', 'wpbooklist_categories_content' );

// Adding the front-end ui css file for this extension
add_action('wp_enqueue_scripts', 'wpbooklist_jre_categories_frontend_ui_style');

// Adding the categories shortcode
add_shortcode('wpbooklist_categories', 'wpbooklist_category_shortcode_function');

// For modifying the width of the view
add_action( 'wp_footer', 'categories_boilerplate_action_javascript' );

// For enabling the auto-converting of WPBookList Libraries 
add_action( 'admin_footer', 'wpbooklist_categories_mobile_action_javascript' );
add_action( 'wp_ajax_wpbooklist_categories_mobile_action', 'wpbooklist_categories_mobile_action_callback' );
add_action( 'wp_ajax_nopriv_wpbooklist_categories_mobile_action', 'wpbooklist_categories_mobile_action_callback' );

// Verifies that the core WPBookList plugin is installed and activated - otherwise, the Extension doesn't load and a message is displayed to the user.
register_activation_hook( __FILE__, 'wpbooklist_categories_core_plugin_required' );

add_filter('wpbooklist_add_sub_menu', 'wpbooklist_category_submenu');
function wpbooklist_category_submenu($submenu_array) {
 	$extra_submenu = array(
		'Categories'
	);
 
	// combine the two arrays
	$submenu_array = array_merge($submenu_array,$extra_submenu);
	return $submenu_array;
}
?>