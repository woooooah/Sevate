<?php
/**
 * Plugin Name:  Sevate Automation Pyramid
 * Plugin URI:   https://sevate.si/
 * Description:  Interactive CIM automation process pyramid with clickable service popups. Use the shortcode [automation_pyramid] to embed the pyramid on any page.
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

class Sevate_Automation_Pyramid {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_shortcode( 'automation_pyramid', array( $this, 'render_pyramid' ) );
	}

	public function enqueue_assets() {
		wp_enqueue_style(
			'sevate-pyramid',
			plugin_dir_url( __FILE__ ) . 'assets/css/pyramid.css',
			array(),
			'1.0.0'
		);
		wp_enqueue_script(
			'sevate-pyramid',
			plugin_dir_url( __FILE__ ) . 'assets/js/pyramid.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);
	}

	/**
	 * Returns the pyramid level & service data.
	 * Edit this array to update levels, services, descriptions and URLs.
	 */
	private function get_levels() {
		return array(
			array(
				'id'       => 'erp',
				'label'    => 'ERP',
				'sublabel' => 'Enterprise Level',
				'services' => array(
					array(
						'name'        => 'ERP Integracija',
						'description' => 'Celovita integracija poslovnih procesov (nabava, prodaja, finance, HR) v enoten informacijski sistem za boljšo preglednost in odločanje.',
						'page_url'    => '/storitve/erp-integracija/',
					),
					array(
						'name'        => 'Poslovna analitika',
						'description' => 'Vzpostavitev naprednih analitičnih orodij za sledenje KPI-jem in podporo strateškim odločitvam vodstva.',
						'page_url'    => '/storitve/poslovna-analitika/',
					),
				),
			),
			array(
				'id'       => 'mes',
				'label'    => 'MES',
				'sublabel' => 'Manufacturing Execution Level',
				'services' => array(
					array(
						'name'        => 'MES Implementacija',
						'description' => 'Vzpostavitev sistema za izvajanje in upravljanje proizvodnje v realnem času — sledenje nalogom, beleženje izpadov in OEE analitika.',
						'page_url'    => '/storitve/mes-implementacija/',
					),
					array(
						'name'        => 'Sledenje proizvodnje',
						'description' => 'Digitalizacija sledenja materialnim tokovom in polizdelkom skozi celoten proizvodni proces.',
						'page_url'    => '/storitve/sledenje-proizvodnje/',
					),
				),
			),
			array(
				'id'       => 'scada',
				'label'    => 'SCADA',
				'sublabel' => 'Supervision Level',
				'services' => array(
					array(
						'name'        => 'SCADA Sistemi',
						'description' => 'Razvoj in implementacija nadzornih sistemov za spremljanje in vodenje industrijskih procesov z grafičnimi vmesniki v realnem času.',
						'page_url'    => '/storitve/scada-sistemi/',
					),
					array(
						'name'        => 'Alarmni sistemi',
						'description' => 'Konfiguracija naprednih alarmnih sistemov z eskalacijskimi protokoli in arhiviranjem dogodkov.',
						'page_url'    => '/storitve/alarmni-sistemi/',
					),
					array(
						'name'        => 'Poročanje & KPI',
						'description' => 'Avtomatsko generiranje poročil o delovanju naprav, porabi energije in kakovosti proizvodnje.',
						'page_url'    => '/storitve/porocanje-kpi/',
					),
				),
			),
			array(
				'id'       => 'plc',
				'label'    => 'PLC / PAC',
				'sublabel' => 'Control Level',
				'services' => array(
					array(
						'name'        => 'PLC Programiranje',
						'description' => 'Razvoj in optimizacija krmilniških programov za avtomatizacijo strojev in procesnih linij (Siemens, Allen-Bradley, Beckhoff).',
						'page_url'    => '/storitve/plc-programiranje/',
					),
					array(
						'name'        => 'Robotska integracija',
						'description' => 'Integracija industrijskih robotov v obstoječe proizvodne linije z varnostnimi in komunikacijskimi protokoli.',
						'page_url'    => '/storitve/robotska-integracija/',
					),
				),
			),
			array(
				'id'       => 'field',
				'label'    => 'Senzorji & Aktuatorji',
				'sublabel' => 'Field Level',
				'services' => array(
					array(
						'name'        => 'Senzorska Integracija',
						'description' => 'Izbira, namestitev in konfiguracija merilnih senzorjev (tlak, temperatura, pretok, položaj) za zanesljiv zajem podatkov.',
						'page_url'    => '/storitve/senzorska-integracija/',
					),
					array(
						'name'        => 'Aktuatorji & Pogoni',
						'description' => 'Integracija elektromotornih pogonov, pnevmatskih in hidravličnih aktuatorjev z ustreznimi varnostnimi sistemi.',
						'page_url'    => '/storitve/aktuatorji-pogoni/',
					),
					array(
						'name'        => 'Industrijske mreže',
						'description' => 'Načrtovanje in vzpostavitev industrijskih komunikacijskih omrežij (PROFIBUS, PROFINET, EtherNet/IP, Modbus).',
						'page_url'    => '/storitve/industrijske-mreze/',
					),
				),
			),
		);
	}

	public function render_pyramid( $atts ) {
		$levels = $this->get_levels();

		ob_start();
		include plugin_dir_path( __FILE__ ) . 'templates/pyramid-template.php';
		return ob_get_clean();
	}
}

new Sevate_Automation_Pyramid();
