<?php
/**
 * Minimus functions and definitions
 *
 * @package Minimus
 */

/**
 * Minimus includes
 *
 * The $minimus_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 */
$minimus_includes = array(
	'inc/options.php',
	'inc/setup.php',
	'inc/scripts.php',
	'inc/navigation.php',
	'inc/sidebars.php',
	'inc/widgets.php',
	'inc/template-tags.php',
	'inc/extras.php',
	'inc/jetpack.php'
);

foreach ( $minimus_includes as $file ) {
	if ( !$filepath = locate_template( $file ) ) {
	  trigger_error( sprintf( __( 'Error locating %s for inclusion', 'minimus' ), $file ), E_USER_ERROR );
	}

  require_once $filepath;
}
unset( $file, $filepath );
