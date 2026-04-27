<?php
/*
Plugin Name: Story Plugin
Description: Custom post type Story with shortcode display
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

/* ============================
   REGISTER CUSTOM POST TYPE
============================ */
function story_register_post_type() {

    $labels = [
        'name' => 'Stories',
        'singular_name' => 'Story',
        'add_new' => 'Add Story',
        'add_new_item' => 'Add New Story',
        'edit_item' => 'Edit Story',
        'new_item' => 'New Story',
        'view_item' => 'View Story',
        'search_items' => 'Search Stories',
    ];

    register_post_type('story', [
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title'],
        'has_archive' => true,
    ]);
}
add_action('init', 'story_register_post_type');

/* ============================
   SHORTCODE
    ============================ */
function story_shortcode() {

    $query = new WP_Query([
        'post_type' => 'story',
        'posts_per_page' => -1,
        'meta_key' => 'leto',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    ]);

    if (!$query->have_posts()) {
        return '<p>No stories found.</p>';
    }

    ob_start();

    echo '<div class="timeline">';

    $i = 0; // counter

    while ($query->have_posts()) {
        $query->the_post();

        $opis  = get_field('opis');
        $image = get_field('slika');
        $leto  = get_field('leto');

        // alternate class
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

        echo '<h3 class="timeline-title">' . get_the_title() . '</h3>';
        echo '<div class="timeline-year">Leto začetka: ' . esc_html($leto) . '</div>';
        echo '<div class="timeline-description">' . esc_html($opis) . '</div>';

        echo '</div>';
        echo '</div>';
        echo '</div>';

        $i++; // increment
    }

    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('story', 'story_shortcode');