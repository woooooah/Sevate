<?php
/*
Plugin Name: Story Plugin
Description: Custom post type Story with shortcode display
Version: 2.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

/* ============================
   REGISTER CUSTOM POST TYPE
============================ */
function story_register_post_type() {

    $labels = [
        'name'          => 'Stories',
        'singular_name' => 'Story',
        'add_new'       => 'Add Story',
        'add_new_item'  => 'Add New Story',
        'edit_item'     => 'Edit Story',
        'new_item'      => 'New Story',
        'view_item'     => 'View Story',
        'search_items'  => 'Search Stories',
    ];

    register_post_type('story', [
        'labels'      => $labels,
        'public'      => true,
        'menu_icon'   => 'dashicons-editor-help',
        'supports'    => ['title'],
        'has_archive' => true,
    ]);
}
add_action('init', 'story_register_post_type');


/* ============================
   REGISTER POST TYPE WITH POLYLANG
============================ */
add_filter('pll_get_post_types', 'add_story_to_polylang');
function add_story_to_polylang($post_types) {
    $post_types['story'] = 'story';
    return $post_types;
}


/* ============================
   SHORTCODE
============================ */
function story_shortcode() {

    $args = [
        'post_type'      => 'story',
        'posts_per_page' => -1,
        'meta_key'       => 'leto',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    ];

    // Filter by current language if Polylang is active
    if (function_exists('pll_current_language')) {
        $args['lang'] = pll_current_language();
    }

    $query = new WP_Query($args);

    // Get current language for static string translations
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'sl';

    if ($lang == 'en') {
        $no_stories = 'No stories found.';
        $year_label  = 'Start year: ';
    } elseif ($lang == 'de') {
        $no_stories = 'Keine Geschichten gefunden.';
        $year_label  = 'Startjahr: ';
    } else {
        $no_stories = 'Ni zgodb.';
        $year_label  = 'Leto začetka: ';
    }

    if (!$query->have_posts()) {
        return '<p>' . esc_html($no_stories) . '</p>';
    }

    ob_start();

    echo '<div class="timeline">';

    $i = 0;

    while ($query->have_posts()) {
        $query->the_post();

        $opis  = get_field('opis');
        $image = get_field('slika');
        $leto  = get_field('leto');

        $alt_class = ($i % 2 === 1) ? ' timeline-item-alt' : '';

        echo '<div class="timeline-item' . $alt_class . '">';
        echo '<div class="timeline-marker"></div>';
        echo '<div class="timeline-content">';

        if ($image) {
            echo '<div class="timeline-image">
                    <img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '">
                  </div>';
        }

        echo '<div class="timeline-text">';
        echo '<h3 class="timeline-title">' . esc_html(get_the_title()) . '</h3>';
        echo '<div class="timeline-year">' . esc_html($year_label) . esc_html($leto) . '</div>';
        echo '<div class="timeline-description">' . esc_html($opis) . '</div>';
        echo '</div>';

        echo '</div>';
        echo '</div>';

        $i++;
    }

    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('story', 'story_shortcode');