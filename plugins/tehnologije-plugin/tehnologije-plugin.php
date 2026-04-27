<?php
/*
Plugin Name: Tehnologije Plugin
Description: Prikaz tehnologij (ACF) kot kartice
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

/* ============================
   CPT: TEHNOLOGIJA
============================ */
function tehnologija_cpt() {

    register_post_type('tehnologija', [
        'labels' => [
            'name' => 'Tehnologije',
            'singular_name' => 'Tehnologija'
        ],
        'public' => true,
        'menu_icon' => 'dashicons-cpu',
        'supports' => ['title'],
    ]);
}
add_action('init', 'tehnologija_cpt');


/* ============================
   SHORTCODE
============================ */
function tehnologije_shortcode() {

    $query = new WP_Query([
        'post_type' => 'tehnologija',
        'posts_per_page' => -1,
    ]);

    if (!$query->have_posts()) {
        return '<p>Ni podatkov.</p>';
    }

    ob_start();

    echo '<div class="tehnologije-grid">';

    while ($query->have_posts()) {
        $query->the_post();

        $slika = get_field('slika');
        $opis = get_field('opis');
        
        $nivo = get_field('nivo');

        $nivo_value = '';
        $nivo_label = '';

        if (is_array($nivo)) {
            $nivo_value = $nivo['value'];
            $nivo_label = $nivo['label'];
        }

        /* mapiranje value -> css class */
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

        echo '<h3 class="tehnologija-title">' . get_the_title() . '</h3>';
        echo '<div class="tehnologija-nivo">' . esc_html($nivo_label) . '</div>';
        echo '<div class="tehnologija-opis">' . esc_html($opis) . '</div>';

        echo '</div>';
    }

    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('tehnologije', 'tehnologije_shortcode');