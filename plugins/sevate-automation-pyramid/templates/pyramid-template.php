<?php
/**
 * Template: Automation Pyramid
 *
 * Available variables:
 *   $levels  array  Pyramid level & service data from Sevate_Automation_Pyramid::get_levels()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total_levels = count( $levels );
?>
<div class="sap-wrapper">

	<div class="sap-pyramid" role="list" aria-label="<?php esc_attr_e( 'CIM Automation Pyramid', 'sevate-automation-pyramid' ); ?>">

		<?php foreach ( $levels as $index => $level ) :
			// Width grows from ~18% at the top to ~82% at the bottom.
			$pct   = $total_levels > 1
				? 18 + ( ( $index / ( $total_levels - 1 ) ) * 64 )
				: 50;
			$width = round( $pct, 2 );
		?>
		<div class="sap-level sap-level--<?php echo esc_attr( $level['id'] ); ?>"
		     style="width:<?php echo esc_attr( $width ); ?>%"
		     role="listitem">

			<div class="sap-level__labels">
				<span class="sap-level__name"><?php echo esc_html( $level['label'] ); ?></span>
				<span class="sap-level__sub"><?php echo esc_html( $level['sublabel'] ); ?></span>
			</div>

			<div class="sap-level__services">
				<?php foreach ( $level['services'] as $service ) : ?>
				<button
					class="sap-service-btn"
					type="button"
					data-name="<?php echo esc_attr( $service['name'] ); ?>"
					data-description="<?php echo esc_attr( $service['description'] ); ?>"
					data-url="<?php echo esc_url( $service['page_url'] ); ?>"
					aria-label="<?php echo esc_attr( $service['name'] ); ?>"
				>
					<span class="sap-service-btn__dot" aria-hidden="true"></span>
					<span class="sap-service-btn__label"><?php echo esc_html( $service['name'] ); ?></span>
				</button>
				<?php endforeach; ?>
			</div>

		</div><!-- .sap-level -->
		<?php endforeach; ?>

	</div><!-- .sap-pyramid -->

	<!-- ─── Popup / Modal ─── -->
	<div class="sap-overlay" id="sap-overlay" aria-hidden="true"></div>

	<div class="sap-popup" id="sap-popup" role="dialog" aria-modal="true" aria-labelledby="sap-popup-title" aria-hidden="true">
		<button class="sap-popup__close" id="sap-popup-close" type="button" aria-label="<?php esc_attr_e( 'Zapri', 'sevate-automation-pyramid' ); ?>">&#x2715;</button>
		<h3 class="sap-popup__title" id="sap-popup-title"></h3>
		<p  class="sap-popup__desc"  id="sap-popup-desc"></p>
		<a  class="sap-popup__link"  id="sap-popup-link" href="#">
			<?php esc_html_e( 'Izvedite več', 'sevate-automation-pyramid' ); ?> &rarr;
		</a>
	</div>

</div><!-- .sap-wrapper -->
