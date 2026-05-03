<?php
/*
Plugin Name: Tehnologije Plugin
Description: Prikaz tehnologij (ACF) kot kartice
Version: 2.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

/* ============================
   CPT: TEHNOLOGIJA
============================ */
function tehnologija_cpt() {

    register_post_type('tehnologija', [
        'labels' => [
            'name'          => 'Tehnologije',
            'singular_name' => 'Tehnologija',
        ],
        'public'    => true,
        'menu_icon' => 'dashicons-cpu',
        'supports'  => ['title'],
    ]);
}
add_action('init', 'tehnologija_cpt');


/* ============================
   REGISTER POST TYPE WITH POLYLANG
============================ */
add_filter('pll_get_post_types', 'add_tehnologija_to_polylang');
function add_tehnologija_to_polylang($post_types) {
    $post_types['tehnologija'] = 'tehnologija';
    return $post_types;
}


/* ============================
   SHORTCODE
============================ */
function tehnologije_shortcode() {

    $args = [
        'post_type'      => 'tehnologija',
        'posts_per_page' => -1,
    ];

    // Filter by current language if Polylang is active
    if (function_exists('pll_current_language')) {
        $args['lang'] = pll_current_language();
    }

    $query = new WP_Query($args);

    // Get current language for static string translations
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'sl';

    if ($lang == 'en') {
        $no_data = 'No data found.';
        $nivo_translations = [
            'osnova' => 'Physics & Energy',
            'jedro'  => 'Logic & Control',
            'vrh'    => 'Vision & Intelligence',
        ];
    } elseif ($lang == 'de') {
        $no_data = 'Keine Daten gefunden.';
        $nivo_translations = [
            'osnova' => 'Physik & Energie',
            'jedro'  => 'Logik & Steuerung',
            'vrh'    => 'Vision & Intelligenz',
        ];
    } else {
        $no_data = 'Ni podatkov.';
        $nivo_translations = [
            'osnova' => 'Fizika & Energetika',
            'jedro'  => 'Logika & Nadzor',
            'vrh'    => 'Vidik & Inteligenca',
        ];
    }


    if (!$query->have_posts()) {
        return '<p>' . esc_html($no_data) . '</p>';
    }

    ob_start();

    echo '<div class="tehnologije-grid">';

    while ($query->have_posts()) {
        $query->the_post();

        $slika = get_field('slika');
        $opis  = get_field('opis');
        $nivo  = get_field('nivo');

        $nivo_value = '';
        $nivo_label = '';

        if (is_array($nivo)) {
            $nivo_value = $nivo['value'];
            // Use translated label instead of the ACF label
            $nivo_label = isset($nivo_translations[$nivo_value])
                ? $nivo_translations[$nivo_value]
                : $nivo['label'];
        }

        $nivo_class = '';

        switch ($nivo_value) {
            case 'osnova':
                $nivo_class = 'tehnologija-osnova';
                break;
            case 'jedro':
                $nivo_class = 'tehnologija-jedro';
                break;
            case 'vrh':
                $nivo_class = 'tehnologija-vrh';
                break;
        }

        echo '<div class="tehnologija-card ' . esc_attr($nivo_class) . '">';

        if ($slika) {
            echo '<div class="tehnologija-image">
                    <img src="' . esc_url($slika['url']) . '" alt="' . esc_attr($slika['alt']) . '">
                  </div>';
        }

        echo '<h3 class="tehnologija-title">' . esc_html(get_the_title()) . '</h3>';
        echo '<div class="tehnologija-nivo">' . esc_html($nivo_label) . '</div>';
        echo '<div class="tehnologija-opis">' . esc_html($opis) . '</div>';

        echo '</div>';
    }

    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('tehnologije', 'tehnologije_shortcode');