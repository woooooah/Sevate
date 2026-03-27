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

/*
 * SVG viewport: 860 × 500 units.  The isosceles triangle has its
 * apex at (430, 0) and base corners at (0, 500) and (860, 500).
 * N equal horizontal bands divide the triangle; each band is a
 * polygon whose left/right edges lie exactly on the triangle sides,
 * guaranteeing a perfectly straight pyramid silhouette.
 */
$vb_w   = 860;
$vb_h   = 500;
$band_h = $vb_h / $total_levels;

$geo = array();
foreach ( $levels as $i => $lv ) {
	$y0  = $i * $band_h;
	$y1  = ( $i + 1 ) * $band_h;
	// x-coordinates of the triangle edge at y0 and y1
	$xl0 = $vb_w / 2 * ( 1 - $y0 / $vb_h );
	$xr0 = $vb_w / 2 * ( 1 + $y0 / $vb_h );
	$xl1 = $vb_w / 2 * ( 1 - $y1 / $vb_h );
	$xr1 = $vb_w / 2 * ( 1 + $y1 / $vb_h );

	// Polygon points: level 0 (apex) is a triangle, others are trapezoids
	if ( 0 === $i ) {
		$pts = round( $vb_w / 2 ) . ',0 '
			. round( $xr1 ) . ',' . round( $y1 ) . ' '
			. round( $xl1 ) . ',' . round( $y1 );
	} else {
		$pts = round( $xl0 ) . ',' . round( $y0 ) . ' '
			. round( $xr0 ) . ',' . round( $y0 ) . ' '
			. round( $xr1 ) . ',' . round( $y1 ) . ' '
			. round( $xl1 ) . ',' . round( $y1 );
	}

	// CSS custom properties for the absolutely-positioned content overlay.
	// --level-pad: keep text inside the band's bottom (widest) edge + 2% margin.
	$geo[ $i ] = array(
		'points'  => $pts,
		'xl1'     => round( $xl1 ),
		'xr1'     => round( $xr1 ),
		'y1'      => round( $y1 ),
		'top_pct' => round( $y0 / $vb_h * 100, 4 ),
		'h_pct'   => round( 100 / $total_levels, 4 ),
		'pad_pct' => max( 2.0, round( $xl1 / $vb_w * 100 + 2, 1 ) ),
	);
}
?>
<div class="sap-wrapper">

	<div class="sap-pyramid" role="list" aria-label="<?php esc_attr_e( 'CIM Automation Pyramid', 'sevate-automation-pyramid' ); ?>">

		<!-- SVG: colored triangle bands that form the pyramid visual -->
		<svg class="sap-pyramid__bg" viewBox="0 0 <?php echo esc_attr( $vb_w ); ?> <?php echo esc_attr( $vb_h ); ?>" aria-hidden="true" focusable="false">
			<?php foreach ( $levels as $i => $level ) : ?>
			<polygon
				class="sap-bg-band sap-bg-band--<?php echo esc_attr( $level['id'] ); ?>"
				points="<?php echo esc_attr( $geo[ $i ]['points'] ); ?>"/>
			<?php endforeach; ?>

			<!-- Separator lines between bands -->
			<?php for ( $i = 0; $i < $total_levels - 1; $i++ ) : ?>
			<line class="sap-divider"
			      x1="<?php echo esc_attr( $geo[ $i ]['xl1'] ); ?>" y1="<?php echo esc_attr( $geo[ $i ]['y1'] ); ?>"
			      x2="<?php echo esc_attr( $geo[ $i ]['xr1'] ); ?>" y2="<?php echo esc_attr( $geo[ $i ]['y1'] ); ?>"/>
			<?php endfor; ?>
		</svg>

		<!-- Level content: absolutely positioned over the SVG -->
		<?php foreach ( $levels as $i => $level ) :
			$g = $geo[ $i ];
		?>
		<div class="sap-level sap-level--<?php echo esc_attr( $level['id'] ); ?>"
		     style="--level-top:<?php echo esc_attr( $g['top_pct'] ); ?>%;--level-h:<?php echo esc_attr( $g['h_pct'] ); ?>%;--level-pad:<?php echo esc_attr( $g['pad_pct'] ); ?>%"
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
