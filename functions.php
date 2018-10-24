<?php

 /**
  * Store the theme's directory path and uri in constants
  */

define('THEME_DIR_PATH', get_template_directory());
define('THEME_DIR_URI', get_template_directory_uri());

/**
 * Enqueue scripts and styles
 */

function _tk_scripts(){

    // load _tk styles
    wp_enqueue_style( '_proton-style', get_stylesheet_uri() );

    // <!-- Bootstrap CSS -->
    wp_enqueue_style( '_proton-bootstrap', THEME_DIR_URI . '/css/bootstrap.min.css' );
    wp_enqueue_style( '_proton-line-icons', THEME_DIR_URI . '/css/line-icons.css' );
    wp_enqueue_style( '_proton-owl.carousel', THEME_DIR_URI . '/css/owl.carousel.css' );
    wp_enqueue_style( '_proton-owl.theme', THEME_DIR_URI . '/css/owl.theme.css' );
    wp_enqueue_style( '_proton-animate', THEME_DIR_URI . '/css/animate.css' );
    wp_enqueue_style( '_proton-magnific-popup', THEME_DIR_URI . '/css/magnific-popup.css' );
    wp_enqueue_style( '_proton-nivo-lightbox', THEME_DIR_URI . '/css/nivo-lightbox.css' );
    wp_enqueue_style( '_proton-main', THEME_DIR_URI . '/css/main.css' );    
    wp_enqueue_style( '_proton-responsive', THEME_DIR_URI . '/css/responsive.css' );

    // <!-- jQuery first, then Tether, then Bootstrap JS. -->
    wp_enqueue_script( 'jQuery', get_template_directory_uri() . '/js/jquery-min.js', array(), '3.3.1', true );

    wp_enqueue_script('_proton-popperjs', THEME_DIR_URI . '/js/popper.min.js', '','', true );    
    // load bootstrap js
    wp_enqueue_script('_proton-bootstrapjs', THEME_DIR_URI . '/js/bootstrap.min.js', '','', true );
    wp_enqueue_script('_proton-owl.carouseljs', THEME_DIR_URI . '/js/owl.carousel.js', '','', true );
    wp_enqueue_script('_proton-mixitupjs', THEME_DIR_URI . '/js/jquery.mixitup.js', '','', true );
    // wp_enqueue_script('_proton-jquery.navjs', THEME_DIR_URI . '/js/jquery.nav.js', '','', true );
    wp_enqueue_script('_proton-scrolling-navjs', THEME_DIR_URI . '/js/scrolling-nav.js', '','', true );
    wp_enqueue_script('_proton-jquery.easingjs', THEME_DIR_URI . '/js/jquery.easing.min.js', '','', true ); 
    wp_enqueue_script('_proton-wowjs', THEME_DIR_URI . '/js/wow.min.js', '','', true );
    wp_enqueue_script('_proton-jquery.counterupjs', THEME_DIR_URI . '/js/jquery.counterup.min.js', '','', true ); 
    wp_enqueue_script('_proton-nivo-lightboxjs', THEME_DIR_URI . '/js/nivo-lightbox.js', '','', true ); 
    wp_enqueue_script('_proton-jquery.magnific-popupjs', THEME_DIR_URI . '/js/jquery.magnific-popup.min.js', '','', true ); 
    wp_enqueue_script('_proton-waypointsjs', THEME_DIR_URI . '/js/waypoints.min.js', '','', true );
    wp_enqueue_script('_proton-mainjs', THEME_DIR_URI . '/js/main.js', '','', true );

    /*
     * Gulp version
     */
    
    // wp_enqueue_style( '_proton-main', THEME_DIR_URI . '/css/stylesheet.css' );
    // wp_enqueue_script('_proton-mainjs', THEME_DIR_URI . '/js/bundle.js', '','', true );
}

add_action( 'wp_enqueue_scripts', '_tk_scripts' );

// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

include_once ('src/functions_tambahan.php');
include_once ('src/functions_tambahan1.php');
include_once ('src/functions_tambahan2.php');
?>