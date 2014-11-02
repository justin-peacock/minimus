<?php

/**
 * Slider
 */

//Add additional generated image sizes
add_image_size( 'slider-image', 1280, 600, true ); //(cropped)

function minimus_slider() {

	$labels = array(
		'name'                => _x( 'Slides', 'Post Type General Name', 'minimus' ),
		'singular_name'       => _x( 'Slider', 'Post Type Singular Name', 'minimus' ),
		'menu_name'           => __( 'Slides', 'minimus' ),
		'parent_item_colon'   => __( 'Parent Slide:', 'minimus' ),
		'all_items'           => __( 'All Slides', 'minimus' ),
		'view_item'           => __( 'View Slide', 'minimus' ),
		'add_new_item'        => __( 'Add New Slide', 'minimus' ),
		'add_new'             => __( 'Add New', 'minimus' ),
		'edit_item'           => __( 'Edit Slide', 'minimus' ),
		'update_item'         => __( 'Update Slide', 'minimus' ),
		'search_items'        => __( 'Search Slide', 'minimus' ),
		'not_found'           => __( 'Not found', 'minimus' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'minimus' ),
	);
	$args = array(
		'label'               => __( 'slider', 'minimus' ),
		'description'         => __( 'Orbit slider post type', 'minimus' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 11,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'slider', $args );

}
add_action( 'init', 'minimus_slider', 0 );

/**
 * Discography Meta Box
 * http://code.tutsplus.com/articles/reusable-custom-meta-boxes-part-1-intro-and-basic-fields--wp-23259
 */
function add_slider_meta_box() {
    add_meta_box(
		'slider_info', // $id
		'Extras', // $title
		'show_slider_meta_box', // $callback
		'slider', // $page
		'side', // $context
		'high'); // $priority
}
add_action('add_meta_boxes', 'add_slider_meta_box');

// Field Array
$prefix = 'slider_';
$slider_meta_fields = array(
	array(
		'label'	=> 'URL',
		'desc'	=> 'Add a link to wrap the slide.',
		'id'	=> $prefix.'url',
		'type'	=> 'text'
	)
);

// The Callback
function show_slider_meta_box() {
	global $slider_meta_fields, $post;
	// Use nonce for verification
	echo '<input type="hidden" name="slider_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<div class="slider-info">';
	foreach ($slider_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<p>
				<label for="'.$field['id'].'">'.$field['label'].'</label>';
				switch($field['type']) {
					// text
					case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" class="widefat" size="30" />
								<br /><span class="description">'.$field['desc'].'</span>';
					break;
				} //end switch
		echo '</p>';
	} // end foreach
	echo '</div>'; // end table
}

// Save the Data
function save_slider_meta($post_id) {
    global $slider_meta_fields;

	// verify nonce
	if (
	  !isset( $_POST['slider_meta_box_nonce'] )
	  || !wp_verify_nonce( $_POST['slider_meta_box_nonce'], basename(__FILE__) )
	)
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}

	// loop through fields and save the data
	foreach ($slider_meta_fields as $field) {
		if($field['type'] == 'tax_select') continue;
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	} // enf foreach

}
add_action('save_post', 'save_slider_meta');

/**
 * Slider Output
 */
function minimus_slider_output() {
    $query = new WP_Query( array(
        'post_type' => 'slider',
        'posts_per_page' => -1,
        'order' => 'ASC'
    ) );

    if ( $query->have_posts() ) { ?>
        <ul class="site-slider" data-orbit>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <?php $post_id = get_the_ID(); $slider_meta_fields = get_post_custom($post_id); ?>
            <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            	<?php if ( has_post_thumbnail() ) : ?>
            		<?php if (!empty( $slider_meta_fields[ 'slider_url' ] ) ) { ?>
            			<a href="<?php echo $slider_meta_fields[ 'slider_url' ][0]; ?>">
            		<?php } ?>
	          			<?php the_post_thumbnail('slider-image', array('class' => 'slider-image')); ?>
          			<?php if (!empty( $slider_meta_fields[ 'slider_url' ] ) ) { ?>
            			</a>
            		<?php } ?>
            	<?php endif; ?>

							<?php
							$thecontent = get_the_content();
							if(!empty($thecontent)) { ?>
	            	<div class="orbit-caption">
									<?php the_content(); ?>
								</div>
							<?php } ?>
            </li>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </ul><!-- .site-slider -->
      <?php
    }
}

/**
 * Slider Shortcode
 */
function minimus_slider_shortcode() {
	ob_start();
	minimus_slider_output();
	$slider = ob_get_clean();
	return $slider;
}
add_shortcode( 'slider', 'minimus_slider_shortcode' );
