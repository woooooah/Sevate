<?php
/**
 * Pyramid level & service data.
 *
 * Single source of truth for all pyramid content.
 * Levels are ordered top → bottom (index 0 = apex).
 *
 * In a future release, get_levels() will read from WordPress Options
 * via a Settings page so admins can edit content without touching code.
 *
 * @package Sevate_Automation_Pyramid
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sevate_Pyramid_Data {

	/**
	 * Returns all pyramid levels with their services.
	 *
	 * Each level:
	 *   id          string  CSS identifier (vidik | logika | fizika)
	 *   label       string  Level name shown in the left annotation panel
	 *   sublabel    string  Short category label shown below the name
	 *   description string  One-sentence explanation of the level's role
	 *   services    array   List of service items (see below)
	 *
	 * Each service:
	 *   name        string  Button label and detail-panel heading
	 *   description string  Detail-panel body text
	 *   page_url    string  "Izvedite več" link target
	 *
	 * @return array<int, array>
	 */
	public static function get_levels() {
		return array(
			array(
				'id'          => 'vidik',
				'label'       => 'Vidik & Inteligenca',
				'sublabel'    => 'Vrh',
				'description' => 'Človeški vmesnik sistema. Podatke iz spodnjih nivojev spremenimo v uporabno informacijo — vizualizacija, nadzor in povezava z višjimi poslovnimi sistemi.',
				'services'    => array(
					array(
						'name'        => 'SCADA sistemi',
						'description' => 'Razvoj nadzornih centrov (WinCC, Ignition, ipd.) za spremljanje celotne proizvodnje v realnem času.',
						'page_url'    => '/storitve/scada-sistemi/',
					),
					array(
						'name'        => 'Operaterski paneli (HMI)',
						'description' => 'Izdelava intuitivnih lokalnih zaslonov za upravljanje strojev neposredno na terenu.',
						'page_url'    => '/storitve/operaterski-paneli/',
					),
					array(
						'name'        => 'MES / ERP',
						'description' => 'Razvoj orodij za zajem podatkov in povezavo z MES ali ERP sistemi — vrh CIM piramide.',
						'page_url'    => '/storitve/mes-erp/',
					),
				),
			),
			array(
				'id'          => 'logika',
				'label'       => 'Logika & Nadzor',
				'sublabel'    => 'Jedro',
				'description' => 'Možgani avtomatiziranega sistema. Fizični svet postane inteligenten — avtomatika, krmilna logika in medsebojna integracija naprav.',
				'services'    => array(
					array(
						'name'        => 'PLC programiranje',
						'description' => 'Razvoj krmilne logike (Siemens, Rockwell, Beckhoff, ipd.) za upravljanje procesov in strojev.',
						'page_url'    => '/storitve/plc-programiranje/',
					),
					array(
						'name'        => 'Sistemska integracija',
						'description' => 'Povezovanje različnih naprav (pogoni, frekvenčni regulatorji, senzorji) v enotno delujoč sistem.',
						'page_url'    => '/storitve/sistemska-integracija/',
					),
					array(
						'name'        => 'Testiranje & zagon',
						'description' => 'Štartanje linij, parametrizacija in optimizacija delovanja sistema v realnem času.',
						'page_url'    => '/storitve/testiranje-zagon/',
					),
				),
			),
			array(
				'id'          => 'fizika',
				'label'       => 'Fizika & Energetika',
				'sublabel'    => 'Osnova',
				'description' => 'Trdni temelji brez katerih višji nivoji ne delujejo. Strojna oprema, elektrika, senzorji in aktuatorji — elektrotehniko in strojništvo združimo v celoto.',
				'services'    => array(
					array(
						'name'        => 'E-plan',
						'description' => 'Projektiranje in izdelava popolne tehnične dokumentacije v skladu z industrijskimi standardi.',
						'page_url'    => '/storitve/e-plan/',
					),
					array(
						'name'        => 'Strojna izvedba',
						'description' => 'Izdelava elektro omaric, kabelskih poti, nabava ustrezne opreme od preverjenih dobaviteljev.',
						'page_url'    => '/storitve/strojna-izvedba/',
					),
					array(
						'name'        => 'Montaža',
						'description' => 'Montaža senzorjev, aktuatorjev in ostale terenske opreme po projektni dokumentaciji.',
						'page_url'    => '/storitve/montaza/',
					),
					array(
						'name'        => 'IO testiranje',
						'description' => 'Preverjanje integritete signalov, testiranje senzorjev in izvršnih elementov – zagotovilo, da "žica drži".',
						'page_url'    => '/storitve/io-testiranje/',
					),
				),
			),
		);
	}
}
