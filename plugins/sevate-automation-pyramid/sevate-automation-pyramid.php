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

/**
 * Load plugin translations for the current locale.
 */
function sevate_automation_pyramid_load_textdomain() {
	$domain = 'sevate-automation-pyramid';
	load_plugin_textdomain(
		$domain,
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);

	$locale = '';
	if ( function_exists( 'pll_current_language' ) ) {
		$locale = pll_current_language( 'locale' );
		if ( empty( $locale ) ) {
			$locale = pll_current_language();
		}
	}
	if ( empty( $locale ) ) {
		$locale = get_locale();
	}
	$locale = apply_filters( 'plugin_locale', $locale, $domain );

	$locale_map = array(
		'en' => 'en_US',
		'de' => 'de_DE',
		'sl' => 'sl_SI',
	);
	if ( isset( $locale_map[ $locale ] ) ) {
		$locale = $locale_map[ $locale ];
	}

	$mofile_candidates = array(
		SAP_PLUGIN_DIR . "languages/{$domain}-{$locale}.mo",
		SAP_PLUGIN_DIR . "languages/{$domain}-" . str_replace( '-', '_', $locale ) . '.mo',
		SAP_PLUGIN_DIR . "languages/{$domain}-" . str_replace( '_', '-', $locale ) . '.mo',
	);

	foreach ( $mofile_candidates as $mofile ) {
		if ( file_exists( $mofile ) ) {
			load_textdomain( $domain, $mofile );
			break;
		}
	}
}
add_action( 'init', 'sevate_automation_pyramid_load_textdomain', 0 );
add_action( 'wp', 'sevate_automation_pyramid_load_textdomain', 0 );

add_action( 'plugins_loaded', function () {
	new Sevate_Automation_Pyramid();
} );

