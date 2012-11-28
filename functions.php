<?php
/**
 * This is the primary functions file for the Tasty child theme.
 *
 * @author     Jared Atchison
 * @since      1.0.0
 * @version    1.0.0
 * @package    Tasty
 * @copyright  Copyright (c) 2012, Jared Atchison
 * @link       http://jaredatchison.com
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @todo       localization
 * @todo       tag chaining
 */

// Child theme infomation
define( 'CHILD_THEME_NAME', 'Tasty Child Theme'                  );
define( 'CHILD_THEME_URL',  'https://github.com/jaredatch/Tasty' );

/** Load child theme translations (do not remove) */
load_child_theme_textdomain( 'ja-tasty-child', apply_filters( 'tasty_child_theme_textdomain', get_stylesheet_directory() . '/languages', 'ja-tasty-child' ) );

// Start the engine
require_once( get_template_directory() . '/lib/init.php' ); // Initialize Genesis Framework
require_once( CHILD_DIR . '/lib/cleanup.php'             ); // Genesis & WordPress cleanup
require_once( CHILD_DIR . '/lib/metabox.php'             ); // Link metabox
require_once( CHILD_DIR . '/lib/bookmark-this.php'       ); // BookmarkThis bookmarklet
require_once( CHILD_DIR . '/lib/loop.php'                ); // Replacement loop
require_once( CHILD_DIR . '/lib/widgets/tag-list.php'    ); // Tag list widget
require_once( CHILD_DIR . '/lib/autocomplete.php'        ); // Tag autocomplete

// Register default Genesis layout
genesis_set_default_layout( 'content-sidebar' );

/**
 * Change the labeling for the "Posts" menu to "Bookmarks"
 *
 * @since 1.0.0
 * @global array $menu
 * @global araay $submenu
 */
function tasty_change_post_menu_label() {

	global $menu;
	global $submenu;

	$menu[5][0]                 = __( 'Bookmarks', 'ja-tasty-child' );
	$submenu['edit.php'][5][0]  = __( 'Bookmarks', 'ja-tasty-child' );
	$submenu['edit.php'][10][0] = __( 'Add Bookmarks', 'ja-tasty-child' );
	$submenu['edit.php'][16][0] = __( 'Tags', 'ja-tasty-child' );

}
add_action( 'admin_menu', 'tasty_change_post_menu_label' );

/**
 * Change post object labels
 *
 * @since 1.0.0
 * @global array $wp_post_types
 */
function tasty_change_post_object_label() {

	global $wp_post_types;

	$labels                     = &$wp_post_types['post']->labels;
	$labels->name               = __( 'Bookmarks', 'ja-tasty-child' );
	$labels->singular_name      = __( 'Bookmark', 'ja-tasty-child' );
	$labels->add_new            = __( 'Add Bookmark', 'ja-tasty-child' );
	$labels->add_new_item       = __( 'Add Bookmark', 'ja-tasty-child' );
	$labels->edit_item          = __( 'Edit Bookmarks', 'ja-tasty-child' );
	$labels->new_item           = __( 'Bookmark', 'ja-tasty-child' );
	$labels->view_item          = __( 'View Bookmark', 'ja-tasty-child' );
	$labels->search_items       = __( 'Search Bookmarks', 'ja-tasty-child' );
	$labels->not_found          = __( 'No Bookmarks found', 'ja-tasty-child' );
	$labels->not_found_in_trash = __( 'No Bookmarks found in Trash', 'ja-tasty-child' );

}
add_action( 'init', 'tasty_change_post_object_label' );

/**
 * Custom CSS for the post edit screen
 *
 * @since 1.0.0
 * @global array $post
 */
function tasty_post_admin_print_styles() {

	wp_register_style( 'tasty-bookmarkthis-css', CHILD_URL . '/lib/css/bookmark-this.css' );
	wp_register_style( 'tasty-post-css',         CHILD_URL . '/lib/css/post.css'          );

    global $post;

    if( $post->post_type == 'post' ){
        wp_enqueue_style( 'tasty-post-css' );
    }
}
add_action( 'admin_print_styles', 'tasty_post_admin_print_styles' );

/**
 * Customize the post columns
 *
 * @since 1.0.0
 * @param array $columns
 * @return array
 */
function tasty_post_edit_columns( $columns ) {
    $columns = array(
        'cb'       => '<input type="checkbox" />',
        'title'    => __( 'Title', 'ja-tasty-child' ),
		'link_url' => __( 'Link', 'ja-tasty-child' ),
		'tags'     => __('Tags', 'ja-tasty-child' ),
		'date'     => __( 'Date Added', 'ja-tasty-child' )
    );
    return $columns;
}
add_action( 'manage_edit-post_columns', 'tasty_post_edit_columns' );

/**
 * The custom column calls
 *
 * @since 1.0.0
 * @param array $column
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
 * Shortcut to grab the custom fields
 *
 * @since 1.0.0
 * @param string $field
 * @global array $post
 * @return false or string
 */
function tasty_get_custom_field( $field ) {
	global $post;
	$value = get_post_meta( $post->ID, $field, true );
	if ( $value ) return $value ;
	else return false;
}

// Move Genesis primary navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before', 'genesis_do_nav' ); 

/**
 * Customize footer to add credit and affilaite link
 *
 * @since 1.0.0
 * @param string $credits
 * @return striing
 */
function tasty_footer_creds_text( $creds ) {
    $creds = '[footer_copyright] <a href="http://jaredatchison.com">Jared Atchison</a> [footer_childtheme_link] ' . sprintf( __( 'on the <a href="%s">Genesis Framework</a>', 'ja-tasty-child' ), 'http://www.jaredatchison.com/go/genesis/' ) . ' &middot; [footer_wordpress_link] &middot; [footer_loginout]';
    return $creds;
}
add_filter( 'genesis_footer_creds_text', 'tasty_footer_creds_text' );

/**
 * Customize search form submit button
 *
 * @since 1.0.0
 * @param string $text
 * @return string
 */
function tasty_search_button_text( $text ) {
    return esc_attr__( 'Go', 'ja-tasty-child' );
}
add_filter( 'genesis_search_button_text', 'tasty_search_button_text' );

/**
 * Customize search form default input value
 *
 * @since 1.0.0
 * @param string $text
 * @return string
 */
function tasty_custom_search_text( $text ) {
    return esc_attr__( 'Search bookmarks', 'ja-tasty-child' );
}
add_filter( 'genesis_search_text', 'tasty_custom_search_text' );

/**
 * Trash alert
 *
 * @since 1.0.0
 * @author Stephanie Leary
 * @link http://sillybean.net/code/themes/twenty-links-a-delicious-inspired-child-theme-for-wordpress/
 */
function tasty_trash_alert(){
	if ( isset( $_GET['trashed'] ) ) {
		echo '<p class="notice">';
		$trashedpost = get_post($_GET['ids']);
		printf( __( '<em>%s</em> has been moved to the <a href="%s">trash</a>.', 'ja-tasty-child' ), $trashedpost->post_title, get_option( 'site_url' ).'/wp-admin/edit.php?post_status=trash&post_type=post' );
		echo '</p>';
	}
}
add_action( 'genesis_before_loop', 'tasty_trash_alert' );

/**
 * Search bar area above results
 *
 * @since 1.0.0
 * @global array $wp_query
 */
function tasty_tag_search_area(){
	?>
	<div id="quick-bar">
		<form action="<?php echo site_url(); ?>/" method="POST" id="tag-search">
		<?php _e( 'Search Tags', 'ja-tasty-child' ); ?>
		<input type="text" name="tag" id="tag" class="tagsearchfield" autocomplete="off" onfocus="setSuggest('tag');" placeholder="<?php _e( 'enter a tag', 'ja-tasty-child' ); ?>" />	
		<input type="hidden" name="oldtags" id="oldtags" value="<?php echo get_query_var('tag'); ?>" />	
		<input type="hidden" name="oldurl" id="oldurl" value="<?php echo $oldurl; ?>" />	
		</form>		
		<div id="total">
			<?php
			global $wp_query;
			echo is_home() ? __( 'Total Bookmarks', 'ja-tasty-child' ) : __( 'Bookmarks', 'ja-tasty-child' );
			?>
			<span id="count"><?php echo $wp_query->found_posts; ?></span>
			<a href="feed/"><img src="<?php echo CHILD_URL; ?>/images/rss.png" alt="<?php _e( 'RSS', 'ja-tasty-child' ); ?>" /></a>
		</div>
	</div>
	<?php
}
add_action( 'genesis_before_loop', 'tasty_tag_search_area' );

/**
 * Redirect single post pages to the link.
 *
 * @since 1.0.0
 * @author Bill Erickson
 */
function tasty_redirect_on_single() {
	if( is_single() ) {
		global $post;
		$url = tasty_get_custom_field( '_tasty_link' );
		if( empty( $url ) ) return;
		wp_redirect( $url, '301' );
		exit;
	}
}
add_action( 'template_redirect', 'tasty_redirect_on_single' );
