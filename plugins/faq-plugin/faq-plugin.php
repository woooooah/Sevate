<?php
/*
Plugin Name: FAQ Plugin
Description: Custom post type FAQ with shortcode display
Version: 2.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

/* ============================
   REGISTER CUSTOM POST TYPE
============================ */
function faq_register_post_type() {

    $labels = [
        'name'          => 'FAQs',
        'singular_name' => 'FAQ',
        'add_new'       => 'Add FAQ',
        'add_new_item'  => 'Add New FAQ',
        'edit_item'     => 'Edit FAQ',
        'new_item'      => 'New FAQ',
        'view_item'     => 'View FAQ',
        'search_items'  => 'Search FAQs',
    ];

    register_post_type('faq', [
        'labels'      => $labels,
        'public'      => true,
        'menu_icon'   => 'dashicons-editor-help',
        'supports'    => ['title'],
        'has_archive' => true,
    ]);
}
add_action('init', 'faq_register_post_type');


/* ============================
   REGISTER POST TYPE WITH POLYLANG
============================ */
add_filter('pll_get_post_types', 'add_faq_to_polylang');
function add_faq_to_polylang($post_types) {
    $post_types['faq'] = 'faq';
    return $post_types;
}


/* ============================
   SHORTCODE
============================ */
function faq_shortcode() {

    $args = [
        'post_type'      => 'faq',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ];

    // Filter by current language if Polylang is active
    if (function_exists('pll_current_language')) {
        $args['lang'] = pll_current_language();
    }

    $query = new WP_Query($args);

    // Static string translations
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'sl';

    if ($lang == 'en') {
        $no_faqs = 'No FAQs found.';
    } elseif ($lang == 'de') {
        $no_faqs = 'Keine FAQs gefunden.';
    } else {
        $no_faqs = 'Ni pogostih vprašanj.';
    }

    if (!$query->have_posts()) {
        return '<p>' . esc_html($no_faqs) . '</p>';
    }

    ob_start();

    $i = 0;
    ?>

    <div class="faq-accordion">
        <h2 class="faq-accordion-title">
            <?php
            if ($lang == 'en') {
                echo 'Frequently Asked Questions';
            } elseif ($lang == 'de') {
                echo 'Häufig gestellte Fragen';
            } else {
                echo 'Pogosta vprašanja';
            }
            ?>
        </h2>
        <?php while ($query->have_posts()) : $query->the_post();
            $odgovor  = get_field('answer');
            $item_id  = 'faq-' . $i;
        ?>
            <div class="faq-accordion-item">
                <div class="faq-accordion-header" id="heading-<?php echo esc_attr($item_id); ?>">
                    <h3 class="faq-question">
                        <button class="faq-accordion-button"
                                aria-expanded="false"
                                aria-controls="content-<?php echo esc_attr($item_id); ?>">
                            <?php echo esc_html(get_the_title()); ?>
                            <span class="faq-accordion-icon">+</span>
                        </button>
                    </h3>
                </div>
                <div class="faq-accordion-content"
                     id="content-<?php echo esc_attr($item_id); ?>"
                     aria-labelledby="heading-<?php echo esc_attr($item_id); ?>">
                    <div class="faq-answer">
                        <?php echo $odgovor; ?>
                    </div>
                </div>
            </div>
        <?php $i++; endwhile; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.faq-accordion-button');

        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                const content = this.closest('.faq-accordion-item').querySelector('.faq-accordion-content');

                this.setAttribute('aria-expanded', !expanded);

                if (!expanded) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    content.style.opacity = '1';
                    this.querySelector('.faq-accordion-icon').textContent = '−';
                } else {
                    content.style.maxHeight = '0';
                    content.style.opacity = '0';
                    this.querySelector('.faq-accordion-icon').textContent = '+';
                }
            });
        });
    });
    </script>

    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('faq', 'faq_shortcode');