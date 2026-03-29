<?php
/**
 * Pyramid level & service data.
 *
 * get_levels() reads from the 'storitev' Custom Post Type and ACF fields:
 *   - sloj_piramide  (select: fizika | logika | vidik)
 *   - vrstni_red     (number: order within the layer)
 *   - kratki_opis    (textarea: short description for the popup)
 *
 * Layer metadata (label, sublabel, description) remains static here
 * because it describes the architectural concept, not individual services.
 *
 * @package Sevate_Automation_Pyramid
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sevate_Pyramid_Data {

	/**
	 * Layer metadata — static architectural definitions.
	 * Ordered top → bottom (vidik → logika → fizika).
	 */
	private static $layer_meta = array(
		'vidik'  => array(
			'label'       => 'Vidik & Inteligenca',
			'sublabel'    => 'Vrh',
			'description' => 'Človeški vmesnik sistema. Podatke iz spodnjih nivojev spremenimo v uporabno informacijo — vizualizacija, nadzor in povezava z višjimi poslovnimi sistemi.',
		),
		'logika' => array(
			'label'       => 'Logika & Nadzor',
			'sublabel'    => 'Jedro',
			'description' => 'Možgani avtomatiziranega sistema. Fizični svet postane inteligenten — avtomatika, krmilna logika in medsebojna integracija naprav.',
		),
		'fizika' => array(
			'label'       => 'Fizika & Energetika',
			'sublabel'    => 'Osnova',
			'description' => 'Trdni temelji brez katerih višji nivoji ne delujejo. Strojna oprema, elektrika, senzorji in aktuatorji — elektrotehniko in strojništvo združimo v celoto.',
		),
	);

	/**
	 * Returns all pyramid levels with their services, built dynamically
	 * from the 'storitev' CPT and ACF meta fields.
	 *
	 * @return array<int, array>
	 */
	public static function get_levels() {
		$grouped = array(
			'vidik'  => array(),
			'logika' => array(),
			'fizika' => array(),
		);

		$query = new WP_Query( array(
			'post_type'      => 'storitev',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'meta_value_num',
			'meta_key'       => 'vrstni_red',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		) );

		foreach ( $query->posts as $post ) {
			$sloj  = get_post_meta( $post->ID, 'sloj_piramide', true );
			$opis  = get_post_meta( $post->ID, 'kratki_opis', true );
			$order = (int) get_post_meta( $post->ID, 'vrstni_red', true );

			if ( ! isset( $grouped[ $sloj ] ) ) {
				continue;
			}

			$grouped[ $sloj ][] = array(
				'name'        => get_the_title( $post ),
				'description' => wp_strip_all_tags( $opis ),
				'url'         => get_permalink( $post ),
				'_order'      => $order,
			);
		}

		wp_reset_postdata();

		// Sort each layer by vrstni_red and strip internal key.
		foreach ( $grouped as &$services ) {
			usort( $services, static function ( $a, $b ) {
				return $a['_order'] - $b['_order'];
			} );
			foreach ( $services as &$s ) {
				unset( $s['_order'] );
			}
		}
		unset( $services, $s );

		// Build final array top → bottom.
		$levels = array();
		foreach ( array_keys( self::$layer_meta ) as $id ) {
			$levels[] = array_merge(
				array( 'id' => $id ),
				self::$layer_meta[ $id ],
				array( 'services' => $grouped[ $id ] )
			);
		}

		return $levels;
	}
}
