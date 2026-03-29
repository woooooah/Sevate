<?php
/**
 * Plugin Name:  Sevate Automation Pyramid
 * Plugin URI:   https://sevate.si/
 * Description:  Interactive CIM automation pyramid with clickable service popups. Use [automation_pyramid] shortcode to embed on any page.
 * Version:      1.0.0
 * Author:       Sevate d.o.o.
 * Author URI:   https://sevate.si/
 * License:      GPL-2.0-or-later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  sevate-automation-pyramid
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SAP_VERSION',    '1.0.0' );
define( 'SAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once SAP_PLUGIN_DIR . 'includes/class-pyramid-data.php';
require_once SAP_PLUGIN_DIR . 'includes/class-pyramid.php';

add_action( 'plugins_loaded', function () {
	new Sevate_Automation_Pyramid();
} );

