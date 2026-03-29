<?php
/**
 * Main plugin class.
 *
 * Registers WordPress hooks and renders the shortcode.
 * Data lives in Sevate_Pyramid_Data.
 * HTML markup lives in templates/pyramid-template.php.
 * All rendering logic is handled client-side (pyramid-data.js + pyramid.js).
 *
 * @package Sevate_Automation_Pyramid
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sevate_Automation_Pyramid {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_shortcode( 'automation_pyramid', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Enqueue plugin stylesheet and scripts.
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'sevate-pyramid',
			SAP_PLUGIN_URL . 'assets/css/pyramid.css',
			array(),
			SAP_VERSION
		);

		// pyramid-data.js must load before pyramid.js.
		wp_enqueue_script(
			'sevate-pyramid-data',
			SAP_PLUGIN_URL . 'assets/js/pyramid-data.js',
			array(),
			SAP_VERSION,
			true
		);

		wp_enqueue_script(
			'sevate-pyramid',
			SAP_PLUGIN_URL . 'assets/js/pyramid.js',
			array( 'jquery', 'sevate-pyramid-data' ),
			SAP_VERSION,
			true
		);
	}

	/**
	 * Shortcode callback: [automation_pyramid]
	 *
	 * @return string HTML output.
	 */
	public function render_shortcode( $atts ) {
		ob_start();
		include SAP_PLUGIN_DIR . 'templates/pyramid-template.php';
		return ob_get_clean();
	}
}
