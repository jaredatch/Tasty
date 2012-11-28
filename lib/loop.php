<?php
/**
 * This file contains the Custom loop goodness.
 *
 * @since   1.0.0
 * @package Tasty
 */

// Remove default Genesis loop
remove_action( 'genesis_loop', 'genesis_do_loop' );

/**
 * Replacement loop. Adapeted from Twenty Links child theme.
 *
 * @since 1.0.0
 * @global int $loop_counter
 * @global array $post
 */
function tasty_do_loop() {
	
	global $loop_counter;
	$loop_counter = 0;
	$first        = TRUE;

	if ( have_posts() ) : while ( have_posts() ) : the_post(); // the loop
	
	global $post;

	$link  = tasty_get_custom_field( '_tasty_link' );
	$title = get_the_title();
	
	/**
	 * Most of the below adapted from TwentyTen Links by Stephanie Leary
	 * 
	 * @link http://sillybean.net/code/themes/twenty-links-a-delicious-inspired-child-theme-for-wordpress/
	 */
	if ( is_new_day() && !$first ) echo "</div><!-- day -->";
	$first = FALSE;
	the_date( 'j M y', '<div class="day"><span class="date">', '</span>', true );
	do_action( 'genesis_before_post' );
	?>
	
	<div <?php post_class(); ?>>

		<h2 class="entry-title"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></h2>
		<?php
		if ( $post->post_content != '' ) {
			echo '<div class="entry-content">';
			the_content();
			echo '</div>';
		}
		?>
		<div class="tasty-meta">
			<div class="entry-tags">
				<?php the_tags( '<span class="a-tag">', '', '</span>' ); ?>
			</div><!-- .entry-tags -->
			<div class="entry-utility">
				<?php edit_post_link( __( 'Edit', 'ja-tasty-child' ), '<span class="edit-link">', '</span>' ); ?>
				<?php if ( current_user_can( 'edit_posts' ) ) { ?> <span class="meta-sep">|</span> <a href="<?php echo get_delete_post_link( $post->ID, '', false ) ?>"><?php _e( 'Delete', 'ja-tasty-child' ); ?></a> <?php } ?>
			</div><!-- .entry-utility -->
		</div><!-- .tasty-meta -->

	</div><!-- .postclass -->
	
	<?php
	do_action( 'genesis_after_post' );
	$loop_counter++;

	endwhile; /** end of one post **/
	echo '</div><!-- .day -->';
	do_action( 'genesis_after_endwhile' );

	else : /** if no posts exist **/
	do_action( 'genesis_loop_else' );
	endif; /** end loop **/
	
}
add_action( 'genesis_loop', 'tasty_do_loop' );
