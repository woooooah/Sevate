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
        'supports' => ['title'],
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
    ?>

    <div class="faq-accordion">
        <?php while ($query->have_posts()) : $query->the_post(); 
            $odgovor = get_field('answer');
            $unique_id = uniqid('faq-');
        ?>
            <div class="faq-accordion-item">
                <div class="faq-accordion-header" id="heading-<?php echo esc_attr($unique_id); ?>">
                    <h3 class="faq-question">
                        <button class="faq-accordion-button" aria-expanded="false" aria-controls="content-<?php echo esc_attr($unique_id); ?>">
                            <?php echo get_the_title(); ?>
                            <span class="faq-accordion-icon">+</span>
                        </button>
                    </h3>
                </div>
                <div class="faq-accordion-content" id="content-<?php echo esc_attr($unique_id); ?>" aria-labelledby="heading-<?php echo esc_attr($unique_id); ?>">
                    <div class="faq-answer">
                        <?php echo $odgovor; ?>
                    </div>
                </div>
            </div>
            <!-- // add hr after each faq item except the last one -->
            <?php if (!$query->current_post == $query->post_count - 1) : ?>
                <hr>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>

    <script>
    (function() {
        const buttons = document.querySelectorAll('.faq-accordion-button');
        
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true' ? false : true;
                const content = this.closest('.faq-accordion-item').querySelector('.faq-accordion-content');
                
                this.setAttribute('aria-expanded', expanded);
                
                if (expanded) {
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
    })();
    </script>

    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('faq', 'faq_shortcode');