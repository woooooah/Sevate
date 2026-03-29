<?php
/**
 * Main plugin class.
 *
 * Registers WordPress hooks and renders the shortcode.
 * Data lives in Sevate_Pyramid_Data (single source of truth).
 * HTML markup lives in templates/pyramid-template.php.
 * All rendering logic is handled client-side by pyramid.js.
 * Data is passed via wp_localize_script (SAP_CONFIG, SAP_LEVELS).
 * pyramid-data.js is only used for standalone test.html testing.
 *
 * @package Sevate_Automation_Pyramid
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sevate_Automation_Pyramid {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 100 );
		add_shortcode( 'automation_pyramid', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Enqueue plugin stylesheet and scripts.
	 * Only fires when the current page actually contains the shortcode.
	 */
	public function enqueue_assets() {
		global $post;

		if (
			! is_a( $post, 'WP_Post' ) ||
			! has_shortcode( $post->post_content, 'automation_pyramid' )
		) {
			return;
		}

		wp_enqueue_style(
			'sevate-pyramid',
			SAP_PLUGIN_URL . 'assets/css/pyramid.css',
			array(),
			SAP_VERSION
		);

		wp_enqueue_script(
			'sevate-pyramid',
			SAP_PLUGIN_URL . 'assets/js/pyramid.js',
			array( 'jquery' ),
			SAP_VERSION,
			true
		);

		// Pass PHP data to JS — single source of truth, pyramid-data.js not loaded in WP.
		wp_localize_script( 'sevate-pyramid', 'SAP_CONFIG', array(
			'VB_W'         => 860,
			'VB_H'         => 690,
			'BAND_HEIGHTS' => array( 270, 180, 240 ),
		) );

		wp_localize_script( 'sevate-pyramid', 'SAP_LEVELS', Sevate_Pyramid_Data::get_levels() );
	}

	/**
	 * Shortcode callback: [automation_pyramid]
	 *
	 * @return string HTML output.
	 */
	public function render_shortcode() {
		ob_start();
		include SAP_PLUGIN_DIR . 'templates/pyramid-template.php';
		return ob_get_clean();
	}
}
