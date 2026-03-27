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
		/*
		 * Pyramid geometry — apex = 12%, base = 88% of container width.
		 * Each level's div width = the BOTTOM edge of its trapezoid band.
		 * The clip-path top-inset is derived so every band's slanted edge
		 * is collinear with every other band's — producing a true pyramid
		 * outline with perfectly straight sides.
		 *
		 *   top_inset = (w_bot - w_top) / (2 * w_bot)
		 *             = step / (2 * w_bot)
		 */
		$t_pct      = 12.0;
		$b_pct      = 88.0;
		$step       = $total_levels > 1
			? ( $b_pct - $t_pct ) / $total_levels
			: $b_pct - $t_pct;
		$w_bot      = round( $t_pct + ( $index + 1 ) * $step, 2 );
		$top_inset  = round( $step / ( 2 * $w_bot ) * 100, 2 );
		$bot_inset  = 100 - $top_inset;
		$clip_path  = "polygon({$top_inset}% 0%, {$bot_inset}% 0%, 100% 100%, 0% 100%)";
		/* Content padding: half the top-inset + 3% safety margin keeps
		 * text and buttons visually inside the trapezoid background. */
		$pad_h      = round( $top_inset / 2 + 3, 1 );
	?>
		<div class="sap-level sap-level--<?php echo esc_attr( $level['id'] ); ?>"
		     style="width:<?php echo esc_attr( $w_bot ); ?>%; --level-clip:<?php echo esc_attr( $clip_path ); ?>; --level-pad:<?php echo esc_attr( $pad_h ); ?>%"
		     role="listitem">

			<div class="sap-level__header">
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
