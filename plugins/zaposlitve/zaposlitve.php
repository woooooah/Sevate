<?php
/*
    Plugin Name: Zaposlitve
*/

function register_zaposlitve() {
    register_post_type('zaposlitve',
        array(
            'labels' => array(
                'name' => 'Zaposlitve',
                'singular_name' => 'zaposlitev'
            ),
            'public' => true, 
            'supports' => ['title', 'thumbnail'],
            'show_in_rest'=>true,
            'rest_base'=>'zaposlitve'
        )
    );
}

add_action('init', 'register_zaposlitve');

add_shortcode('zaposlitve', 'zaposlitve');

function zaposlitve() {
    $vseZaposlitve = new WP_Query(array('post_type' => 'zaposlitve'));
    $output = '<div class = "zaposlitve">';
    $danes = new DateTime();

    while($vseZaposlitve->have_posts()){
        $vseZaposlitve->the_post();
        // print_r(get_post_meta(get_the_ID()));

        $ime = get_the_title();
        $tipArray = get_field_object('tip_zaposlitve');
        $tip = $tipArray['choices'][ $tipArray['value'] ];
        $opis = get_field('opis_delovnega_mesta');
        $placa = get_post_meta(get_the_ID(), 'placa', true);
        
        $rok_prijave_raw = get_post_meta(get_the_ID(), 'rok_prijave', true);
        
        if($rok_prijave_raw) {
            $datum = DateTime::createFromFormat('Ymd', $rok_prijave_raw);
            if($datum < $danes) {
                continue;
            }
            $rok_prijave = 'Rok prijave: ' . $datum->format('j. n. Y');
        } else {
            $rok_prijave = 'Rok prijave: ni določen';
        }
        $output .= '<div class = "zaposlitev">';
        $output .= '<h2>' . $ime . '</h2>';
        $output .= '<h3>' . $tip . '</h3>';
        $output .= '<p>' . $placa . '  |  ' . $rok_prijave . '</p>';
        $output .= '<p>' . $opis . '</p>';
        $output .= '<a href="#prijava" class="btn-prijava">Prijavi se</a>';
        $output .= '</div>';
    }

    $output .= '</div>';
    wp_reset_postdata();
    return $output;
}

