<?php
/**
 * This is custom links metabox used for the links.
 *
 * @since    1.0.0
 * @package  Tasty
 */

/**
 * Register metaboxs
 *
 * @since 1.0.0
 */
function tasty_metaboxes() {
    add_meta_box( 'tasty_link', __( 'Link', 'ja-tasty-child' ), 'tasty_link_metabox', 'post', 'normal' );
}
add_action( 'admin_init', 'tasty_metaboxes');

/**
 * Link metabox
 *
 * @since 1.0.0
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
			'meta_key' => '_tasty_link',
			'meta_value' => $link
		);
		$dupe = new WP_Query( $dupe_args );
		while ( $dupe->have_posts() ): $dupe->the_post();
			echo '<div class="dupe">' . __( 'Page already bookmarked!', 'ja-tasty-child' ) . ' ';
			edit_post_link( '' . __( '(edit)', 'ja-tasty-child' ) . '' );
			echo '<br /><a href="' . get_permalink() . '">' . get_the_title() . '</a> '; 
			the_tags( '<br />' . __( 'Tagged:', 'ja-tasty-child' ) . ' ', ', ' );
			echo '</div>';
		endwhile;
	} 

	echo 'Link: <input name="tasty_link" type="text" style="width:80%" value="' . $link . '" />';

}

/**
 * Save metabox
 *
 * @since 1.0.0
 * @param int $post_id
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
	$link = esc_attr( $_POST['tasty_link'] );
	update_post_meta( $post_id, '_tasty_link', $link );

}
add_action( 'save_post', 'tasty_link_metabox_save_post' );
