<?php
/**
 * Minimus functions and definitions
 *
 * @package Minimus
 */

/**
 * Leroy Wilson Drums includes
 *
 * The $lwd_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 */
$lwd_includes = array(
    'inc/options.php',
    'inc/scripts.php',
    'inc/topbar.php',
    'inc/slider.php',
    'inc/widgets.php',
    'inc/template-tags.php',
    'inc/extras.php',
    'inc/jetpack.php'
);

foreach ( $lwd_includes as $file ) {
    if ( !$filepath = locate_template( $file ) ) {
        trigger_error( sprintf( __( 'Error locating %s for inclusion', 'lwd' ), $file ), E_USER_ERROR );
    }

    require_once $filepath;
}
unset( $file, $filepath );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'minimus_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function minimus_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Minimus, use a find and replace
	 * to change 'minimus' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'minimus', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'minimus' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'minimus_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // minimus_setup
add_action( 'after_setup_theme', 'minimus_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function minimus_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'minimus' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer', 'minimus' ),
		'id'            => 'footer-1',
		'description'   => '',
		'before_widget' => '<li><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside></li>',
		'before_title'  => '<h6 class="widget-title">',
		'after_title'   => '</h6>',
	) );
}
add_action( 'widgets_init', 'minimus_widgets_init' );

/**
 * Add Font Awesome to Admin
 */
function minumus_admin_styles() {

	wp_deregister_style( 'minumus-font-awesome' );
	wp_register_style( 'minumus-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', false, '4.2.0' );
	wp_enqueue_style( 'minumus-font-awesome' );

}
add_action( 'admin_enqueue_scripts', 'minumus_admin_styles' );

/**
 * Admin Icons
 */
function minumus_admin_icons() { ?>
	<style type="text/css" media="screen">
		#adminmenu #menu-posts-slider .menu-icon-post div.wp-menu-image:before {
			font-family: 'FontAwesome' !important;
		}
		#adminmenu #menu-posts-slider .menu-icon-post div.wp-menu-image:before {
			content: '\f03e';
		}
	</style>
<?php

}
add_action( 'admin_head', 'minumus_admin_icons' );
