<?php
/**
 * Scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.css
 *
 * Enqueue scripts in the following order:
 * 2. /theme/assets/js/vendor/modernizr.min.js
 * 3. /theme/assets/js/scripts.js (in footer)
 *
 */
function minimus_scripts() {
	/**
	 * The build task in Grunt renames production assets with a hash
	 * Read the asset names from assets-manifest.json
	 */
	if (WP_ENV === 'development') {
		$assets = array(
			'css'       => '/assets/css/main.css',
			'rtl'       => '/assets/css/main-rtl.css',
			'child'     => '/style.css',
			'fonts'     => '//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700',
			'icons'     => '/assets/css/font-awesome.css',
			'js'        => '/assets/js/scripts.js',
			'modernizr' => '/bower_components/modernizr/modernizr.js',
			'livereload'  => '//localhost:35729/livereload.js'
		);
	} else {
		$get_assets = file_get_contents(get_template_directory() . '/assets/manifest.json');
		$assets     = json_decode($get_assets, true);
		$assets     = array(
			'css'       => '/assets/css/main.min.css?' . $assets['assets/css/main.min.css']['hash'],
			'rtl'       => '/assets/css/main-rtl.css?' . $assets['assets/css/main.min.css']['hash'],
			'child'     => '/style.css?' . $assets['assets/css/main.min.css']['hash'],
			'fonts'     => '//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700?' . $assets['assets/css/main.min.css']['hash'],
			'icons'     => '/assets/css/font-awesome.min.css?' . $assets['assets/css/main.min.css']['hash'],
			'js'        => '/assets/js/scripts.min.js?' . $assets['assets/js/scripts.min.js']['hash'],
			'modernizr' => '/assets/js/vendor/modernizr.min.js'
		);
	}

	wp_enqueue_style('minimus_css', get_template_directory_uri() . $assets['css'], false, null);
	wp_enqueue_style('minimus_fonts', $assets['fonts'], false, null);
	wp_enqueue_style('minimus_icons', get_template_directory_uri() . $assets['icons'], false, null);

	if ( is_child_theme() ){
		wp_enqueue_style('minimus_child', get_stylesheet_directory_uri() . $assets['child'], false, null);
	}

	if ( is_rtl() ) {
		wp_enqueue_style('minimus_rtl', get_template_directory_uri() . $assets['rtl'], false, null);
	}

	if ( is_single() && comments_open() && get_option('thread_comments') ) {
		wp_enqueue_script('comment-reply');
	}

	wp_enqueue_script('modernizr', get_template_directory_uri() . $assets['modernizr'], array(), null, false);
	wp_enqueue_script('jquery');
	wp_enqueue_script('minimus_js', get_template_directory_uri() . $assets['js'], array(), null, true);

	if (WP_ENV === 'development') {
		wp_enqueue_script( 'livereload', $assets['livereload'], '', false, true );
	}
}
add_action('wp_enqueue_scripts', 'minimus_scripts', 100);

/**
 * Add conditional IE styles and scripts
 */
function minimus_ie_scripts() {
		?>
		<!--[if lt IE 9]>
				<script src="<?php echo get_template_directory_uri(); ?>/assets/js/ie.js" type="text/javascript"></script>
				<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/rem-fallback.css">
		<![endif]-->
		<?php
}
add_action( 'wp_head', 'minimus_ie_scripts', 8 );

/**
 * Google Analytics snippet from HTML5 Boilerplate
 *
 * Cookie domain is 'auto' configured. See: http://goo.gl/VUCHKM
 */
function minimus_google_analytics() { ?>
<script>
  <?php if (WP_ENV === 'production') : ?>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
    function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
    e=o.createElement(i);r=o.getElementsByTagName(i)[0];
    e.src='//www.google-analytics.com/analytics.js';
    r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
  <?php else : ?>
    function ga() {
      console.log('GoogleAnalytics: ' + [].slice.call(arguments));
    }
  <?php endif; ?>
  ga('create','<?php echo get_option('minimus_options')['ga']; ?>','auto');ga('send','pageview');
</script>

<?php }
if (get_option('minimus_options')['ga'] && (WP_ENV !== 'production' || !current_user_can('manage_options'))) {
  add_action('wp_footer', 'minimus_google_analytics', 20);
}
