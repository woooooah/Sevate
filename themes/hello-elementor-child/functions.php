<?php
/**
 * Hello Elementor Child Theme functions
 */

// ============================================================
// Custom Post Type: Storitve
// ============================================================
function sevate_register_cpt_storitve() {
    register_post_type( 'storitev', array(
        'labels' => array(
            'name'               => 'Storitve',
            'singular_name'      => 'Storitev',
            'add_new'            => 'Dodaj novo',
            'add_new_item'       => 'Dodaj storitev',
            'edit_item'          => 'Uredi storitev',
            'view_item'          => 'Poglej storitev',
            'all_items'          => 'Vse storitve',
            'search_items'       => 'Išči storitve',
            'not_found'          => 'Ni najdenih storitev',
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'storitve' ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-hammer',
        'menu_position'      => 5,
    ));
}
add_action( 'init', 'sevate_register_cpt_storitve' );

// Nalaganje style.css na vse strani
function hello_elementor_child_styles() {
    wp_enqueue_style(
        'child-style',
        get_stylesheet_uri()
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_styles' );
