<?php
function hello_elementor_child_styles() {
    wp_enqueue_style(
        'child-style',
        get_stylesheet_uri()
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_styles' );