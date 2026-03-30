<?php
/*
Plugin Name: FAQ Plugin
Description: Custom post type FAQ with shortcode display
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

/* ============================
   REGISTER CUSTOM POST TYPE
============================ */
function faq_register_post_type() {

    $labels = [
        'name' => 'FAQs',
        'singular_name' => 'FAQ',
        'add_new' => 'Add FAQ',
        'add_new_item' => 'Add New FAQ',
        'edit_item' => 'Edit FAQ',
        'new_item' => 'New FAQ',
        'view_item' => 'View FAQ',
        'search_items' => 'Search FAQs',
    ];

    register_post_type('faq', [
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title', 'editor'],
        'has_archive' => true,
    ]);
}
add_action('init', 'faq_register_post_type');

/* ============================
   SHORTCODE
============================ */
function faq_shortcode() {

    $query = new WP_Query([
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ]);

    if (!$query->have_posts()) {
        return '<p>No FAQs found.</p>';
    }

    ob_start();

    echo '<div class="faq-container">';

    while ($query->have_posts()) {
        $query->the_post();

        echo '<div class="faq-item">';
        echo '<h3 class="faq-question">' . get_the_title() . '</h3>';
        echo '<div class="faq-answer">' . get_the_content() . '</div>';
        echo '</div>';
    }

    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('faq', 'faq_shortcode');