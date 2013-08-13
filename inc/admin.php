<?php
/**
 * Tasty child theme.
 *
 * @package      TastyChildTHeme
 * @since        2.0.0
 * @copyright    Copyright (c) 2013, Jared Atchison
 * @author       Jared Atchison <contact@jaredatchison.com>
 * @license      GPL-2.0+
 */

/**
 * Custom CSS for the post edit screen
 *
 * @since  2.0.0
 * @global array $post
 */
function tasty_post_admin_print_styles() {
	wp_register_style( 'tasty-bookmarkthis', CHILD_URL . '/css/bookmark-this.css' );
	wp_register_style( 'tasty-post',         CHILD_URL . '/css/post.css'          );

	if( 'post' == get_post_type() )
		wp_enqueue_style( 'tasty-post' );
}
add_action( 'admin_enqueue_scripts', 'tasty_post_admin_print_styles' );

/**
 * Change the labeling for the "Posts" menu to "Bookmarks"
 *
 * @since  2.0.0
 * @global array $menu
 * @global array $submenu
 */
function tasty_change_post_menu_label() {
	global $menu;
	global $submenu;

	$menu[5][0]                 = __( 'Bookmarks',     'ja-tasty-child' );
	$submenu['edit.php'][5][0]  = __( 'Bookmarks',     'ja-tasty-child' );
	$submenu['edit.php'][10][0] = __( 'Add Bookmarks', 'ja-tasty-child' );
	$submenu['edit.php'][16][0] = __( 'Tags',          'ja-tasty-child' );
}
add_action( 'admin_menu', 'tasty_change_post_menu_label' );

/**
 * Change post object labels
 *
 * @since  2.0.0
 * @global array $wp_post_types
 */
function tasty_change_post_object_label() {
	global $wp_post_types;

	$labels                     = &$wp_post_types['post']->labels;
	$labels->name               = __( 'Bookmarks',                   'ja-tasty-child' );
	$labels->singular_name      = __( 'Bookmark',                    'ja-tasty-child' );
	$labels->add_new            = __( 'Add Bookmark',                'ja-tasty-child' );
	$labels->add_new_item       = __( 'Add Bookmark',                'ja-tasty-child' );
	$labels->edit_item          = __( 'Edit Bookmarks',              'ja-tasty-child' );
	$labels->new_item           = __( 'Bookmark',                    'ja-tasty-child' );
	$labels->view_item          = __( 'View Bookmark',               'ja-tasty-child' );
	$labels->search_items       = __( 'Search Bookmarks',            'ja-tasty-child' );
	$labels->not_found          = __( 'No Bookmarks found',          'ja-tasty-child' );
	$labels->not_found_in_trash = __( 'No Bookmarks found in Trash', 'ja-tasty-child' );
}
add_action( 'init', 'tasty_change_post_object_label' );

/**
 * Customize the post columns
 *
 * @since  2.0.0
 * @param  array $columns
 * @return array
 */
function tasty_post_edit_columns( $columns ) {
	$columns = array(
		'cb'       => '<input type="checkbox" />',
		'title'    => __( 'Title',      'ja-tasty-child' ),
		'link_url' => __( 'Link',       'ja-tasty-child' ),
		'tags'     => __( 'Tags',       'ja-tasty-child' ),
		'date'     => __( 'Date Added', 'ja-tasty-child' )
	);
	return $columns;
}
add_action( 'manage_edit-post_columns', 'tasty_post_edit_columns' );

/**
 * The custom column calls
 *
 * @since  2.0.0
 * @param  array $column
 * @global array $post
 */
function tasty_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'link_url':
			$link = tasty_get_custom_field( '_tasty_link' );
			echo $link;
			break;
	}
}
add_action( 'manage_posts_custom_column', 'tasty_custom_columns' );

/**
 * Register URL metabox
 *
 * @since 2.0.0
 */
function tasty_metaboxes() {
    add_meta_box( 'tasty_link', __( 'Link', 'ja-tasty-child' ), 'tasty_link_metabox', 'post', 'normal' );
}
add_action( 'admin_init', 'tasty_metaboxes');

/**
 * Link metabox
 *
 * @since  2.0.0
 * @global array $post
 */
function tasty_link_metabox() {

	global $post;
	
	if ( isset( $_GET['u'] ) && isset( $_GET['t'] ) ) {
		$link = isset( $_GET['u'] ) ? esc_url( $_GET['u'] ) : '';
		$saved_link = '';
	} else {
		$link = get_post_meta( $post->ID, '_tasty_link', true );
		$saved_link = $link;
	}

	wp_nonce_field( 'tasty_link', 'tasty_noncename' );

	// Check for dupes!
	if ( !empty( $link ) && $link != $saved_link ) {
		$dupe_args = array(
			'posts_per_page' => '-1',
			'meta_key'       => '_tasty_link',
			'meta_value'     => $link
		);
		$dupe = new WP_Query( $dupe_args );
		while ( $dupe->have_posts() ): $dupe->the_post();
			echo '<div class="dupe">' . __( 'Page already bookmarked!', 'ja-tasty-child' ) . ' ';
			edit_post_link( '' . __( '(edit)', 'ja-tasty-child' ) . '' );
			echo '<br /><a href="' . get_permalink() . '">' . get_the_title() . '</a> '; 
			the_tags( '<br />' . __( 'Tagged:', 'ja-tasty-child' ) . ' ', ', ' );
			echo '</div>';
		endwhile;
		wp_reset_postdata();
	} 

	echo _e( 'Link', 'ja-tasty-child' ) .  '<input name="tasty_link" type="text" style="width:90%" value="' .  $link . '" />';
}

/**
 * Save metabox
 *
 * @since  2.0.0
 * @param  int $post_id
 * @global array $post
 */
function tasty_link_metabox_save_post( $post_id ){

	global $post;

	// Run through permission checks and what not
    if ( get_post_type() != 'post')
		return;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( !wp_verify_nonce( $_POST['tasty_noncename'], 'tasty_link' ) )
        return;

    if ( !current_user_can( 'edit_post', $post_id ) )
    	return;

    // We made it this far so let's save
	$link = esc_url( $_POST['tasty_link'] );
	update_post_meta( $post_id, '_tasty_link', $link );
}
add_action( 'save_post', 'tasty_link_metabox_save_post' );

/**
 * Show message when bookmark was successfully added
 *
 * @since 2.0.0
 */
function tasty_bookmark_confirm() {
	if ( isset( $_GET['u'] ) && $_GET['u'] == 1 && 'post' == get_post_type() ) {
		?>
		<div id="bookmark-confirm">
			<div class="inner"><?php _e( 'Bookmark Saved', 'ja-tasty-child' ); ?> <a href="javascript:window.close();" class="button button-primary button-large"><?php _e( 'Close Window', 'ja-tasty-child' ); ?></a></div>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'tasty_bookmark_confirm' );