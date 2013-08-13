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

// Remove post thumbnail support
remove_theme_support( 'post-thumbnails' );

// Disable admin bar
add_filter( 'show_admin_bar',  '__return_false' );

// Disable visual editor
add_filter( 'user_can_richedit' , '__return_false' , 50 );

/**
 * Remove admin menu items
 *
 * @since 2.0.0
 */
function tasty_remove_admin_menus(){
	remove_menu_page( 'upload.php'              ); // Media
	remove_menu_page( 'edit-comments.php'       ); // Comments
	remove_menu_page( 'edit.php?post_type=page' ); // Pages
	remove_submenu_page( 'edit.php','edit-tags.php?taxonomy=category' ); // Categories
}
add_action( 'admin_menu', 'tasty_remove_admin_menus' );

/**
 * Remove admin bar menu items
 *
 * @since  2.0.0
 * @global array $wp_admin_bar
 */
function tasty_remove_admin_bar_items() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'new-page',  'new-content' ); // Pages
	$wp_admin_bar->remove_menu( 'new-media', 'new-content' ); // Media
}
add_action( 'wp_before_admin_bar_render', 'tasty_remove_admin_bar_items' );

/**
 * Remove dashboard widgets
 *
 * @since 2.0.0
 */
function tasty_remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_recent_drafts',   'dashboard', 'core' );
	remove_meta_box( 'dashboard_plugins',         'dashboard', 'core' );
	remove_meta_box( 'dashboard_primary',         'dashboard', 'core' );
	remove_meta_box( 'dashboard_secondary',       'dashboard', 'core' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_incoming_links',  'dashboard', 'core' );
	remove_meta_box( 'dashboard_quick_press',     'dashboard', 'core' );
}
add_action( 'admin_menu', 'tasty_remove_dashboard_widgets' );

/**
 * Remove widgets
 *
 * @since 2.0.0
 */
function tasty_remove_widgets() { 
	unregister_widget( 'WP_Widget_Pages'           );
	unregister_widget( 'WP_Widget_Calendar'        );
	unregister_widget( 'WP_Widget_Links'           );
	unregister_widget( 'WP_Widget_Meta'            );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_Recent_Posts'    );
	unregister_widget( 'WP_Widget_Categories'      );	
	unregister_widget( 'WP_Widget_Search'          );
	unregister_widget( 'WP_Nav_Menu_Widget'        );
	unregister_widget( 'WP_Widget_Tag_Cloud'       );
}
add_action( 'widgets_init', 'tasty_remove_widgets', 1 );

/**
 * Remove post metaboxes
 *
 * @since 2.0.0
 */
function tasty_remove_wordpress_metaboxes(){
	remove_meta_box( 'categorydiv',      'post', 'side'   ); // categories
	remove_meta_box( 'postcustom',       'post', 'normal' ); // custom fields
	remove_meta_box( 'postexcerpt',      'post', 'normal' ); // exerpt
	remove_meta_box( 'commentstatusdiv', 'post', 'normal' ); // comments status
	remove_meta_box( 'commentsdiv',      'post', 'normal' ); // comments
	remove_meta_box( 'authordiv',        'post', 'normal' ); // author
	remove_meta_box( 'trackbacksdiv',    'post', 'normal' ); // trackbacks
	remove_meta_box( 'revisionsdiv',     'post', 'normal' ); // revisions
}
add_action( 'admin_menu', 'tasty_remove_wordpress_metaboxes', 50 );