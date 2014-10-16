<?php

/**
 * Theme Options
 * Example output
 * <?php echo get_option('minimus_options')['variable_name']; ?>
 */

function minimus_get_theme_option($name, $default = '') {

	$options = get_option('minimus_options', array());

	if (isset($options[$name])) {
		return $options[$name];
	} else {
		return $default;
	}
}


add_action( 'admin_init', 'options_init' );
add_action( 'admin_menu', 'options_add_page' );

/**
 * Init plugin options to white list our options
 */
function options_init() {
	register_setting( 'minimus_options', 'minimus_options', 'options_validate' );
}

/**
 * Load up the menu page
 */
function options_add_page() {
	add_theme_page( __( 'Options', 'minimus' ), __( 'Options', 'minimus' ), 'edit_options', 'options', 'options_do_page' );
}

/**
 * Create the options page
 */
function options_do_page() {
	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php $current_theme = wp_get_theme(); ?>
		<?php screen_icon(); echo "<h2>" . $current_theme->get( 'Name' ) . __( ' Options', 'minimus' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'minimus' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'minimus_options' ); ?>
			<?php $options = get_option( 'minimus_options' ); ?>
		<h3><?php _e('Social Settings', 'minimus'); ?></h3>
			<table class="form-table">
				<?php
				/**
				 * Google Analytics ID
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Google Analytics ID', 'minimus' ); ?></th>
					<td>
						<input id="minimus_options[ga]" class="regular-text" type="text" name="minimus_options[ga]" value="<?php esc_attr_e( $options['ga'] ); ?>" />
						<label class="description" for="minimus_options[ga]"><?php _e( 'UA-XXXXXXXX-X', 'minimus' ); ?></label>
					</td>
				</tr>
				<?php
				/**
				 * Twitter URL
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Twitter', 'minimus' ); ?></th>
					<td>
						<input id="minimus_options[twitter]" class="regular-text" type="text" name="minimus_options[twitter]" value="<?php esc_attr_e( $options['twitter'] ); ?>" />
						<label class="description" for="minimus_options[twitter]"><?php _e( 'http://twitter.com/username', 'minimus' ); ?></label>
					</td>
				</tr>
				<?php
				/**
				 * Facebook URL
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Facebook', 'minimus' ); ?></th>
					<td>
						<input id="minimus_options[facebook]" class="regular-text" type="text" name="minimus_options[facebook]" value="<?php esc_attr_e( $options['facebook'] ); ?>" />
						<label class="description" for="minimus_options[facebook]"><?php _e( 'http://facebook.com/username', 'minimus' ); ?></label>
					</td>
				</tr>
				<?php
				/**
				 * Google+ URL
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Google+', 'minimus' ); ?></th>
					<td>
						<input id="minimus_options[google-plus]" class="regular-text" type="text" name="minimus_options[google-plus]" value="<?php esc_attr_e( $options['google-plus'] ); ?>" />
						<label class="description" for="minimus_options[google-plus]"><?php _e( 'http://plus.google.com/username', 'minimus' ); ?></label>
					</td>
				</tr>
				<?php
				/**
				 * Linkedin URL
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Linkedin', 'minimus' ); ?></th>
					<td>
						<input id="minimus_options[linkedin]" class="regular-text" type="text" name="minimus_options[linkedin]" value="<?php esc_attr_e( $options['linkedin'] ); ?>" />
						<label class="description" for="minimus_options[linkedin]"><?php _e( 'http://linkedin.com/username', 'minimus' ); ?></label>
					</td>
				</tr>
				<?php
				/**
				 * Instagram URL
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Instagram', 'minimus' ); ?></th>
					<td>
						<input id="minimus_options[instagram]" class="regular-text" type="text" name="minimus_options[instagram]" value="<?php esc_attr_e( $options['instagram'] ); ?>" />
						<label class="description" for="minimus_options[instagram]"><?php _e( 'http://instagram.com/username', 'minimus' ); ?></label>
					</td>
				</tr>
				<?php
				/**
				 * YouTube URL
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'YouTube', 'minimus' ); ?></th>
					<td>
						<input id="minimus_options[youtube]" class="regular-text" type="text" name="minimus_options[youtube]" value="<?php esc_attr_e( $options['youtube'] ); ?>" />
						<label class="description" for="minimus_options[youtube]"><?php _e( 'http://youtube.com/username', 'minimus' ); ?></label>
					</td>
				</tr>
				<?php
				/**
				 * Vimeo URL
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Vimeo', 'minimus' ); ?></th>
					<td>
						<input id="minimus_options[vimeo]" class="regular-text" type="text" name="minimus_options[vimeo]" value="<?php esc_attr_e( $options['vimeo'] ); ?>" />
						<label class="description" for="minimus_options[vimeo]"><?php _e( 'http://vimeo.com/username', 'minimus' ); ?></label>
					</td>
				</tr>
			</table>


			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'minimus' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function options_validate( $input ) {

	// Say our text option must be safe text with no HTML tags
	$input['ga'] = wp_filter_nohtml_kses( $input['ga'] );
	$input['twitter'] = wp_filter_nohtml_kses( $input['twitter'] );
	$input['facebook'] = wp_filter_nohtml_kses( $input['facebook'] );
	$input['google-plus'] = wp_filter_nohtml_kses( $input['google-plus'] );
	$input['instagram'] = wp_filter_nohtml_kses( $input['instagram'] );
	$input['linkedin'] = wp_filter_nohtml_kses( $input['linkedin'] );
	$input['youtube'] = wp_filter_nohtml_kses( $input['youtube'] );
	$input['vimeo'] = wp_filter_nohtml_kses( $input['vimeo'] );

	return $input;
}

/**
 * Connections
 */
function minimus_connections() {
	$networks = array(
		'twitter' => '',
		'facebook' => '',
		'youtube' => '',
		'instagram' => '',
		'linkedin' => '',
		'google-plus' => '',
		'vimeo' => '',
		'pinterest' => '');
	$html = '';
	foreach ($networks as $network => $prefix) {
		if (!empty(get_option('minimus_options')[$network])) {
			$href = get_option('minimus_options')[$network];
			$html .= '<li>';
				$html .= '<a href="' . $href . '" class="' . $network . '-icon">';
					$html .= '<i class="fa fa-' . $network . $prefix . '"></i>';
				$html .= '</a>';
			$html .= '</li>';
		}
	}
	if (!empty($html)) {
		echo '<ul class="connections-list">' . $html . '</ul>';
	}
}
