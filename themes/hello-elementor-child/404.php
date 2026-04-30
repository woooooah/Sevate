<?php get_header(); ?>

<?php
$lang = function_exists('pll_current_language') ? pll_current_language() : 'sl';

if($lang == 'en') {
    $title = 'Oops, this page does not exist!';
    $button = 'Home page';
} elseif($lang == 'de') {
    $title = 'Ups, diese Seite existiert nicht!';
    $button = 'Startseite';
} else {
    $title = 'Ups, ta stran ne obstaja!';
    $button = 'Domača stran';
}
?>

<div style="text-align: center; padding: 80px 20px;">
    <h1><?php echo $title; ?></h1>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="
        display: inline-block;
        margin-top: 20px;
        padding: 12px 28px;
        background-color: #415A77;
        color: #ffffff;
        text-decoration: none;
        border-radius: 4px;
        font-size: 16px;
    ">
        <?php echo $button; ?>
    </a>
</div>

<?php get_footer(); ?>