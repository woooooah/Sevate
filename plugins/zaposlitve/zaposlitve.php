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
            'show_in_rest' => true,
            'rest_base' => 'zaposlitve'
        )
    );
}
add_action('init', 'register_zaposlitve');

// Register post type za Polylang
add_action('pll_init', 'register_zaposlitve_polylang');
function register_zaposlitve_polylang() {
    pll_register_post_type('zaposlitve');
}

add_shortcode('zaposlitve', 'zaposlitve');

function zaposlitve() {
    $args = array(
        'post_type' => 'zaposlitve',
    );

    // filter po trenutnem jeziku ce je polylang active
    if(function_exists('pll_current_language')) {
        $args['lang'] = pll_current_language();
    }

    $vseZaposlitve = new WP_Query($args);
    $output = '<div class="zaposlitve">';
    $danes = new DateTime();

    // prevod staticnih stringov glede na jezik
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'sl';
    
    if($lang == 'en') {
        $rok_label = 'Application deadline: ';
        $rok_none = 'No deadline set';
        $prijavi_se = 'Apply';
    } elseif($lang == 'de') {
        $rok_label = 'Bewerbungsfrist: ';
        $rok_none = 'Keine Frist festgelegt';
        $prijavi_se = 'Bewerben';
    } else {
        $rok_label = 'Rok prijave: ';
        $rok_none = 'Rok prijave: ni določen';
        $prijavi_se = 'Prijavi se';
    }

    while($vseZaposlitve->have_posts()){
        $vseZaposlitve->the_post();

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
            $rok_prijave = $rok_label . $datum->format('j. n. Y');
        } else {
            $rok_prijave = $rok_none;
        }

        $output .= '<div class="zaposlitev">';
        $output .= '<h2>' . $ime . '</h2>';
        $output .= '<h3>' . $tip . '</h3>';
        $output .= '<div class="placa_rok"><p>' . $placa . '  |  ' . $rok_prijave . '</p></div>';
        $output .= '<p>' . $opis . '</p>';
        $output .= '<div class="prijava-btn"><a href="#prijava" class="btn-prijava">' . $prijavi_se . '</a></div>';
        $output .= '</div>';
    }

    $output .= '</div>';
    wp_reset_postdata();
    return $output;
}