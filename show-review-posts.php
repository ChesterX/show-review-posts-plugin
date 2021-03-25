<?php

/**
 * @link              simpals.com
 * @since             1.0.0
 * @package           show_review_posts
 *
 * @wordpress-plugin
 * Plugin Name:       Show review posts
 * Plugin URI:        simpals.com
 * Description:       This is a custom plugin for show posts from desired category
 * Version:           1.1.3
 * Author:            Simpals Dev
 * Author URI:        simpals.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       show_review_posts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SHOW_REVIEW_POSTS_VERSION', '1.1.3' );
define( 'PHP_REQUIRES_VERSION', '7.2' );
define( 'PLUGIN_SLUG', 'srp' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since  1.0.0
 */


/**
 * Include files
 */
require_once dirname( __FILE__ ) . '/show-review-posts-admin.php';
//include( dirname( __FILE__ ) . '/update.php' );


/**
 * Required Hooks
 */
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'srp_flush_rewrites' );


/**
 * Flush rewrite rules on activation
 */
function srp_flush_rewrites() {
	// call your CPT registration function here (it should also be hooked into 'init')
	srp_create_custom_post_type();
	flush_rewrite_rules();
}


// test fillter
//add_filter ('pre_set_site_transient_update_plugins', 'display_transient_update_plugins');
function display_transient_update_plugins( $transient ) {
	var_dump( $transient );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-show_custom_posts_-activator.php
 */
function srp_activate_show_review_posts() {
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-show_custom_posts_-deactivator.php
 */
function srp_deactivate_show_review_posts() {
}

// register_activation_hook( __FILE__, 'sr_activate_show_review_posts' );
// register_deactivation_hook( __FILE__, 'sr_deactivate_show_review_posts' );


/**
 * Enqueue scripts.
 */
function srp_plugin_enqueue_styles() {

	// include CSS files
	wp_enqueue_style( 'show-reviews-plugin', plugins_url( 'assets/style.css', __FILE__ ) );

	// Include JS
	wp_enqueue_script( 'show-reviews-plugin-js', plugins_url( 'assets/script.js', __FILE__ ), array(), '1.0.0', true ); // Print in footer
}

add_action( 'wp_enqueue_scripts', 'srp_plugin_enqueue_styles' );


/**
 * Create post type 'srp_review_posts'
 */
if ( ! function_exists( 'srp_create_custom_post_type' ) ) {

// Register Custom Post Type
	function srp_create_custom_post_type() {

		$labels = array(
			'name'                  => _x( 'Reviews', 'Post Type General Name', 'show_review_posts' ),
			'singular_name'         => _x( 'Review', 'Post Type Singular Name', 'show_review_posts' ),
			'menu_name'             => __( 'Reviews', 'show_review_posts' ),
			'name_admin_bar'        => __( 'Reviews', 'show_review_posts' ),
			'archives'              => __( 'Item Archives', 'show_review_posts' ),
			'attributes'            => __( 'Item Attributes', 'show_review_posts' ),
			'parent_item_colon'     => __( 'Parent Item:', 'show_review_posts' ),
			'all_items'             => __( 'All Items', 'show_review_posts' ),
			'add_new_item'          => __( 'Add New Item', 'show_review_posts' ),
			'add_new'               => __( 'Add New', 'show_review_posts' ),
			'new_item'              => __( 'New Item', 'show_review_posts' ),
			'edit_item'             => __( 'Edit Item', 'show_review_posts' ),
			'update_item'           => __( 'Update Item', 'show_review_posts' ),
			'view_item'             => __( 'View Item', 'show_review_posts' ),
			'view_items'            => __( 'View Items', 'show_review_posts' ),
			'search_items'          => __( 'Search Item', 'show_review_posts' ),
			'not_found'             => __( 'Not found', 'show_review_posts' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'show_review_posts' ),
			'featured_image'        => __( 'Featured Image', 'show_review_posts' ),
			'set_featured_image'    => __( 'Set featured image', 'show_review_posts' ),
			'remove_featured_image' => __( 'Remove featured image', 'show_review_posts' ),
			'use_featured_image'    => __( 'Use as featured image', 'show_review_posts' ),
			'insert_into_item'      => __( 'Insert into item', 'show_review_posts' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'show_review_posts' ),
			'items_list'            => __( 'Items list', 'show_review_posts' ),
			'items_list_navigation' => __( 'Items list navigation', 'show_review_posts' ),
			'filter_items_list'     => __( 'Filter items list', 'show_review_posts' ),
		);
		$args   = array(
			'label'               => __( 'Review', 'show_review_posts' ),
			'description'         => __( 'Hapigood Reviews', 'show_review_posts' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'taxonomies'          => array( 'srp_review_tax_cat' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-testimonial',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		);
		register_post_type( 'srp_review_posts', $args );

	}

	add_action( 'init', 'srp_create_custom_post_type', 0 );

}


/**
 * Create taxonomies
 */
if ( ! function_exists( 'srp_create_reviews_taxonomy' ) ) {

// Register Custom Taxonomy
	function srp_create_reviews_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Categories', 'Taxonomy General Name', 'show_review_posts' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'show_review_posts' ),
			'menu_name'                  => __( 'Category', 'show_review_posts' ),
			'all_items'                  => __( 'All Items', 'show_review_posts' ),
			'parent_item'                => __( 'Parent Item', 'show_review_posts' ),
			'parent_item_colon'          => __( 'Parent Item:', 'show_review_posts' ),
			'new_item_name'              => __( 'New Item Name', 'show_review_posts' ),
			'add_new_item'               => __( 'Add New Item', 'show_review_posts' ),
			'edit_item'                  => __( 'Edit Item', 'show_review_posts' ),
			'update_item'                => __( 'Update Item', 'show_review_posts' ),
			'view_item'                  => __( 'View Item', 'show_review_posts' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'show_review_posts' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'show_review_posts' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'show_review_posts' ),
			'popular_items'              => __( 'Popular Items', 'show_review_posts' ),
			'search_items'               => __( 'Search Items', 'show_review_posts' ),
			'not_found'                  => __( 'Not Found', 'show_review_posts' ),
			'no_terms'                   => __( 'No items', 'show_review_posts' ),
			'items_list'                 => __( 'Items list', 'show_review_posts' ),
			'items_list_navigation'      => __( 'Items list navigation', 'show_review_posts' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'show_in_rest'               => true,
		);
		register_taxonomy( 'srp_review_tax_cat', array( 'srp_review_posts' ), $args );

	}
	add_action( 'init', 'srp_create_reviews_taxonomy', 0 );

}


/**
 * Shortcode
 * [show_review_posts  show_on_home="" posts_per_page=""  category_id=""]
 *
 */
function srp_generate_review_posts( $atts ) {

	// default values
//	define( 'DEFAULT_REVIEWS_LINK', get_home_url() . '/reviews/' );

	// default attributes
	$atts = shortcode_atts( [
		'show_on_home'      => 0,
		'posts_per_page'    => 5,
		'category_id'       => '',
//		'more_reviews_link' => DEFAULT_REVIEWS_LINK,
//		'post_type'         => 'srp_review_posts',
	], $atts );

	// get setup values
	$show_on_home_state = intval( trim( $atts['show_on_home'] ) );
	// $post_type_slug     = trim( $atts['post_type'] );
	$post_type_slug     = 'srp_review_posts';
	$category_id        = trim( $atts['category_id'] );
	$posts_per_page     = $atts['posts_per_page']; // it must be a string type!

	// custom query args setup
	$currentPage = get_query_var( 'paged' );
	$args        = array(
	  'post_type'      => $post_type_slug ,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'cat'            => $category_id,
		'posts_per_page' => $posts_per_page,
		'paged'          => $currentPage
	);

	$custom_query = new WP_Query( $args );
	ob_start();

	// take plugin options
	$options_main_link   = get_option( 'srp_options' );
	$options_review_link = get_option( 'srp_review_link_option' );
	$options_more_reviews_link = get_option( 'srp_more_reviews_link_option' );
	?>

	<div class="show-review-posts-row">

		<div class="logos-img">
			<a href="<?php echo $options_main_link['srp_main_logo_link_field']; ?>" target="_blank">
				<img src="<?php echo plugins_url( 'assets/images/hapigood-logo.png', __FILE__ ) ?>" class="hapigood-logo"
						 alt="hapigood logo">
			</a>

			<a href="<?php echo $options_review_link['srp_review_logo_link_field']; ?>" target="_blank"
				 class="write-review-btn-link">
				<img src="<?php echo plugins_url( 'assets/images/write-review-button.png', __FILE__ ) ?>"
						 class="write-review-btn"
						 alt="write review btn">
			</a>
		</div>

	  <?php
	  if ( $custom_query->have_posts() ) :

		  while ( $custom_query->have_posts() ) : $custom_query->the_post();
			  $post_id = get_the_ID(); ?>

						<article class="review-posts-article">

							<header class="review-posts-entry-header">

								<span class="review-author-name">
									<b>by</b>
									<?php

										// get author name
										if ( ! empty( $post_id ) ) {

											// Get the custom post class.
											$review_author_name = get_post_meta( $post_id, 'srp_author_name_meta', true );

											// If a post class was input, sanitize it and add it to the post class array.
											if ( ! empty( $review_author_name ) ) {
												echo $review_author_name;
											} else {
												echo get_the_author();
											}
										}

										?>
								</span>


								<?php
								// get link address
								if ( ! empty( $post_id ) ) {

									// Get the custom post class.
									$author_description_text = get_post_meta( $post_id, 'srp_author_description_meta', true );

									// If a post class was input, sanitize it and add it to the post class array.
									if ( ! empty( $author_description_text )   ) { ?>
										<span class="review-author-description">
											<?php echo $author_description_text ?>
										</span>
									<?php }
								}
								?>

								<span class="review-posts-date">
									<?php the_time( 'd / m / y' ); ?>
								</span>


							<?php
							// get link address
							if ( ! empty( $post_id ) ) {

								// Get the custom post class.
								$review_link = get_post_meta( $post_id, 'srp_review_link_meta', true );
								$review_link_text = get_post_meta( $post_id, 'srp_review_link_text_meta', true );

								// If a post class was input, sanitize it and add it to the post class array.
								if ( ! empty( $review_link ) && ! empty( $review_link_text )  ) { ?>
														<a href="<?php echo $review_link ?>" class="review-posts-link-to-source" target="_blank">
									<?php echo $review_link_text ?>
														</a>
								<?php }
							}
							?>
							</header><!-- .review-posts-entry-header -->

							<div class="review-posts-entry-content">
								<p>
				  				<?php echo wp_trim_words( get_the_excerpt(), 30, __( ' ...' ) ); ?>
								</p>
							</div><!-- .review-posts-entry-content -->

							<div class="review-posts-full-content">
				  			<?php the_content(); ?>
							</div><!-- .eview-posts-full-content -->

							<footer class="review-posts-entry-footer">
								<span class="link-full-review">
									<?php _e( 'Full review', 'show_review_posts' ); ?>
								</span>
								<span class="close-link-full-review">
									<?php _e( 'Close full review', 'show_review_posts' ); ?>
								</span>
								<!--								<a href="--><?php //the_permalink() ?><!--" class="link-full-review" >-->
								<!--					--><?php //_e( 'Full review', 'show_review_posts' ); ?>
								<!--								</a>-->
							</footer><!-- .review-posts-entry-footer -->
						</article><!-- .article -->


		  <?php

		  endwhile;

		  if ( $show_on_home_state == 0 ) :
			  echo paginate_links( array( 'total' => $custom_query->max_num_pages ) );
		  endif;

		  wp_reset_postdata();
	  endif;

	  if ( $show_on_home_state != 0 ) :

		    ?>
				<a href="<?php echo $options_more_reviews_link['srp_more_reviews_link_field'] ?>" class="more-button">
					<?php _e( 'More reviews', 'show_review_posts' ); ?>
				</a>
	  <?php endif; ?>
	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'show_review_posts', 'srp_generate_review_posts' );


/**
 * Metaboxes
 */
add_action( 'load-post.php', 'srp_meta_boxes_setup' );
add_action( 'load-post-new.php', 'srp_meta_boxes_setup' );

// Meta box setup function.
function srp_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'srp_add_post_meta_boxes' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'srp_save_author_meta', 10, 2 );
	add_action( 'save_post', 'srp_save_author_description_meta', 10, 2 );
	add_action( 'save_post', 'srp_save_link_meta', 10, 2 );
	add_action( 'save_post', 'srp_save_link_text_meta', 10, 2 );
}


// add_meta_boxes action hook
function srp_add_post_meta_boxes() {

	add_meta_box(
		'srp-review-author-name',                                       // Unique ID
		esc_html__( 'Review author', 'show_review_posts' ),      // Title
		'srp_review_author_meta_box_html',                          // Callback function
		'srp_review_posts',                            // Admin page (or post type)
		'side',                                      // Context
		'default'                                      // Priority
	);

	add_meta_box(
		'srp-review-author-description',
		esc_html__( 'Review author description', 'show_review_posts' ),
		'srp_review_author_description_meta_box_html',
		'srp_review_posts',
		'side',
		'default'
	);

	add_meta_box(
		'srp-review-link',
		esc_html__( 'Review Link', 'show_review_posts' ),
		'srp_review_link_meta_box_html',
		'srp_review_posts',
		'side',
		'default'
	);

	add_meta_box(
		'srp-review-link-text',
		esc_html__( 'Review Link Text', 'show_review_posts' ),
		'srp_review_link_text_meta_box_html',
		'srp_review_posts',
		'side',
		'default'
	);

}

// Display the author post meta box.
function srp_review_author_meta_box_html( $post ) { ?>

	<?php //wp_nonce_field( basename( __FILE__ ), 'srp_author_name_meta_nonce' ); ?>

	<p>
		<label for="srp-review-author-name">
		<?php _e( "Add the name of the author of the review.", 'show_review_posts' ); ?>
		</label>
		<br/>
		<input class="widefat" type="text" name="srp-review-author-name" id="srp-review-author-name"
					 value="<?php echo esc_attr( get_post_meta( $post->ID, 'srp_author_name_meta', true ) ); ?>"/>
	</p>
<?php }

// Display the author description post meta box.
function srp_review_author_description_meta_box_html( $post ) { ?>

	<?php //wp_nonce_field( basename( __FILE__ ), 'srp_author_name_meta_nonce' ); ?>

	<p>
		<label for="srp-review-author-description">
		<?php _e( "Add the description of the author of the review.", 'show_review_posts' ); ?>
		</label>
		<br/>
		<input class="widefat" type="text" name="srp-review-author-description" id="srp-review-author-description"
					 value="<?php echo esc_attr( get_post_meta( $post->ID, 'srp_author_description_meta', true ) ); ?>"/>
	</p>
<?php }

// Display the review link meta box.
function srp_review_link_meta_box_html( $post ) { ?>

	<?php //wp_nonce_field( basename( __FILE__ ), 'srp_author_name_meta_nonce' ); ?>

	<p>
		<label for="srp-review-link">
		<?php _e( "Link:", 'show_review_posts' ); ?>
		</label>
		<br/>
		<input class="widefat" type="text" name="srp-review-link" id="srp-review-link"
					 value="<?php echo esc_attr( get_post_meta( $post->ID, 'srp_review_link_meta', true ) ); ?>"/>
	</p>
<?php }

// Display the review link text meta box.
function srp_review_link_text_meta_box_html( $post ) { ?>

	<?php //wp_nonce_field( basename( __FILE__ ), 'srp_author_name_meta_nonce' ); ?>

	<p>
		<label for="srp-review-link-text">
		<?php _e( "Display:", 'show_review_posts' ); ?>
		</label>
		<br/>
		<input class="widefat" type="text" name="srp-review-link-text" id="srp-review-link-text"
					 value="<?php echo esc_attr( get_post_meta( $post->ID, 'srp_review_link_text_meta', true ) ); ?>"/>
	</p>
<?php }

// Save the Author post metadata.
function srp_save_author_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
//	if ( ! isset( $_POST['srp_author_name_meta_nonce'] ) || ! wp_verify_nonce( $_POST['srp_author_name_meta_nonce'], basename( __FILE__ ) ) ) {
//		return $post_id;
//	}

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	/* Get the posted data and sanitize it for use as an HTML class. */
	// $new_meta_value = ( isset( $_POST['srp-review-author-name'] ) ? sanitize_html_class( $_POST['srp-review-author-name'] ) : ’ );
	$new_meta_value = $_POST['srp-review-author-name'];

	/* Get the meta key. */
	$meta_key = 'srp_author_name_meta';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && ’ == $meta_value ) {
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	} /* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
		update_post_meta( $post_id, $meta_key, $new_meta_value );
	} /* If there is no new meta value but an old value exists, delete it. */
		elseif ( ’ == $new_meta_value && $meta_value ) {
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}

// Save the Author Description post metadata.
function srp_save_author_description_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
//	if ( ! isset( $_POST['srp_author_name_meta_nonce'] ) || ! wp_verify_nonce( $_POST['srp_author_name_meta_nonce'], basename( __FILE__ ) ) ) {
//		return $post_id;
//	}

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	/* Get the posted data and sanitize it for use as an HTML class. */
	// $new_meta_value = ( isset( $_POST['srp-review-author-name'] ) ? sanitize_html_class( $_POST['srp-review-author-name'] ) : ’ );
	$new_meta_value = $_POST['srp-review-author-description'];

	/* Get the meta key. */
	$meta_key = 'srp_author_description_meta';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && ’ == $meta_value ) {
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	} /* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
		update_post_meta( $post_id, $meta_key, $new_meta_value );
	} /* If there is no new meta value but an old value exists, delete it. */
		elseif ( ’ == $new_meta_value && $meta_value ) {
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}

// Save the link post metadata.
function srp_save_link_meta( $post_id, $post ) {

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = $_POST['srp-review-link'];

	/* Get the meta key. */
	$meta_key = 'srp_review_link_meta';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && ’ == $meta_value ) {
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	} /* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
		update_post_meta( $post_id, $meta_key, $new_meta_value );
	} /* If there is no new meta value but an old value exists, delete it. */
		elseif ( ’ == $new_meta_value && $meta_value ) {
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}

// Save the link text post metadata.
function srp_save_link_text_meta( $post_id, $post ) {

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = $_POST['srp-review-link-text'];

	/* Get the meta key. */
	$meta_key = 'srp_review_link_text_meta';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && ’ == $meta_value ) {
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	} /* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
		update_post_meta( $post_id, $meta_key, $new_meta_value );
	} /* If there is no new meta value but an old value exists, delete it. */
		elseif ( ’ == $new_meta_value && $meta_value ) {
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}


/**
 * Plugin update functions
 */
//require dirname( __FILE__ ) . '/plugin-update-checker/plugin-update-checker.php';
//$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//	'http://localhost/server_plugin_update/plugin.json',
//	__FILE__, //Full path to the main plugin file or functions.php.
//	'show_review_posts'
//);

require dirname( __FILE__ ) . '/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/josanua/show-review-posts-plugin.git',
	__FILE__,
	'show_review_posts'
);

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('7f60dfadd6224151b7845bc57ed93728332b6adc');

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('show-review-posts-main-branch');