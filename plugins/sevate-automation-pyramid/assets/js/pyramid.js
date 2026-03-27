/* global jQuery */
(function ($) {
	'use strict';

	$(document).ready(function () {
		var $overlay = $('#sap-overlay');
		var $popup   = $('#sap-popup');

		/**
		 * Open popup and populate it with service data.
		 */
		function openPopup(name, description, url) {
			$('#sap-popup-title').text(name);
			$('#sap-popup-desc').text(description);
			$('#sap-popup-link').attr('href', url);

			$overlay.addClass('is-visible').attr('aria-hidden', 'false');
			$popup.addClass('is-visible').attr('aria-hidden', 'false');

			// Move focus into popup for accessibility.
			$('#sap-popup-close').trigger('focus');
		}

		/**
		 * Close popup.
		 */
		function closePopup() {
			$overlay.removeClass('is-visible').attr('aria-hidden', 'true');
			$popup.removeClass('is-visible').attr('aria-hidden', 'true');
		}

		// ── Open on service button click ──────────────────────────
		$(document).on('click', '.sap-service-btn', function (e) {
			e.stopPropagation();

			var name = $(this).data('name');
			var desc = $(this).data('description');
			var url  = $(this).data('url');

			openPopup(name, desc, url);
		});

		// ── Close via X button ────────────────────────────────────
		$(document).on('click', '#sap-popup-close', function () {
			closePopup();
		});

		// ── Close via overlay click ───────────────────────────────
		$(document).on('click', '#sap-overlay', function () {
			closePopup();
		});

		// ── Close via ESC key ─────────────────────────────────────
		$(document).on('keydown', function (e) {
			if (e.key === 'Escape' && $popup.hasClass('is-visible')) {
				closePopup();
			}
		});

		// ── Prevent clicks inside popup from closing it ───────────
		$(document).on('click', '#sap-popup', function (e) {
			e.stopPropagation();
		});

		// ── SVG band hover sync ───────────────────────────────────
		// When the mouse enters interactive content (header / services),
		// pass the hover signal to the corresponding SVG polygon so the
		// band brightens.  A short timeout prevents a flash when the
		// pointer moves between the header and services of the same level.
		var hoverTimers = {};

		$(document).on('mouseenter', '.sap-level__header, .sap-level__services', function () {
			var m = $(this).closest('.sap-level')[0].className.match(/sap-level--(\w+)/);
			if ( ! m ) { return; }
			var id = m[1];
			clearTimeout( hoverTimers[ id ] );
			$( '.sap-bg-band--' + id ).addClass( 'is-hovered' );
		} ).on( 'mouseleave', '.sap-level__header, .sap-level__services', function () {
			var m = $(this).closest('.sap-level')[0].className.match(/sap-level--(\w+)/);
			if ( ! m ) { return; }
			var id = m[1];
			hoverTimers[ id ] = setTimeout( function () {
				$( '.sap-bg-band--' + id ).removeClass( 'is-hovered' );
			}, 60 );
		} );
	});

}(jQuery));
