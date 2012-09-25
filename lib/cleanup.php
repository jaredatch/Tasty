<?php
/**
 * This file removes functionality that is not needed from WordPress and
 * the Genesis Framework.
 *
 * @since   1.0.0
 * @package Tasty
 */

/** Genesis Framework ********************************************************/

// Remove the post meta
remove_action( 'genesis_after_post_content',  'genesis_post_meta'      );

// Remove the post info
remove_action( 'genesis_before_post_content', 'genesis_post_info'      );

// Remove breadcrumbs
remove_action( 'genesis_before_loop',         'genesis_do_breadcrumbs' );

// Remove unused Genesis profile options 
remove_action( 'show_user_profile', 'genesis_user_options_fields' );
remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
remove_action( 'show_user_profile', 'genesis_user_seo_fields'     );
remove_action( 'edit_user_profile', 'genesis_user_seo_fields'     );
remove_action( 'show_user_profile', 'genesis_user_layout_fields'  );
remove_action( 'edit_user_profile', 'genesis_user_layout_fields'  );

// Remove Genesis layouts
genesis_unregister_layout( 'sidebar-content'         );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

// Remove Genesis sidebar
unregister_sidebar( 'sidebar-alt' );

/** 
 * Remove Genesis widgets
 *
 * @since 1.0.0
 */
function tasty_remove_genesis_widgets() {
    unregister_widget( 'Genesis_eNews_Updates'          );
    unregister_widget( 'Genesis_Featured_Page'          );
    unregister_widget( 'Genesis_User_Profile_Widget'    );
    unregister_widget( 'Genesis_Menu_Pages_Widget'      );
    unregister_widget( 'Genesis_Widget_Menu_Categories' );
    unregister_widget( 'Genesis_Featured_Post'          );
    unregister_widget( 'Genesis_Latest_Tweets_Widget'   );
}
add_action( 'widgets_init', 'tasty_remove_genesis_widgets', 20 );

/**
 * Remove Genesis metaboxes from Theme Settings page
 *
 * @since 1.0.0
 * @param string $_genesis_theme_settings_pagehook
 */
function tasty_remove_metaboxes( $_genesis_theme_settings_pagehook ) {
	remove_meta_box( 'genesis-theme-settings-header',     $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-comments',   $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-posts',      $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-blogpage',   $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );
}
add_action( 'genesis_theme_settings_metaboxes', 'tasty_remove_metaboxes' );

/** WordPress Core ***********************************************************/

// Remove post thumbnail support
remove_theme_support( 'post-thumbnails' );

// Disable admin bar
add_filter( 'show_admin_bar',     '__return_false'                           );

// Disable visual editor
add_filter( 'user_can_richedit' , create_function('' , 'return false;') , 50 );

/**
 * Remove admin menu items
 *
 * @since 1.0.0
 */
function tasty_remove_admin_menus(){
    remove_menu_page( 'upload.php'              ); // Media
    remove_menu_page( 'link-manager.php'        ); // Links (pre 3.5)
    remove_menu_page( 'edit-comments.php'       ); // Comments
    remove_menu_page( 'edit.php?post_type=page' ); // Pages
	remove_submenu_page( 'edit.php','edit-tags.php?taxonomy=category' ); // Categories
}
add_action( 'admin_menu', 'tasty_remove_admin_menus' );

/**
 * Remove admin bar menu items
 *
 * @since 1.0.0
 * @global $wp_admin_bar
 */
function tasty_remove_admin_bar_items() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'new-link',  'new-content' ); // Links
	$wp_admin_bar->remove_menu( 'new-page',  'new-content' ); // Pages
	$wp_admin_bar->remove_menu( 'new-media', 'new-content' ); // Media
}
add_action( 'wp_before_admin_bar_render', 'tasty_remove_admin_bar_items' );

/**
 * Remove dashboard widgets
 *
 * @since 1.0.0
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
 * @since 1.0.0
 */
function tasty_remove_widgets() { 
	unregister_widget( 'WP_Widget_Pages'           );
	unregister_widget( 'WP_Widget_Calendar'        );
	unregister_widget( 'WP_Widget_Links'           );
	unregister_widget( 'WP_Widget_Meta'            );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_Categories'      );	
}
add_action( 'widgets_init', 'tasty_remove_widgets', 1 );

/**
 * Remove post metaboxes
 *
 * @since 1.0.0
 */
function tasty_remove_genesis_metaboxes(){
	remove_meta_box( 'genesis_inpost_seo_box',    'post', 'normal' ); // genesis seo
	remove_meta_box( 'genesis_inpost_layout_box', 'post', 'normal' ); // genesis layout
	remove_meta_box( 'categorydiv',               'post', 'side'   ); // categories
	remove_meta_box( 'postcustom',                'post', 'normal' ); // custom fields
	remove_meta_box( 'postexcerpt',               'post', 'normal' ); // exerpt
	remove_meta_box( 'commentstatusdiv',          'post', 'normal' ); // comments status
	remove_meta_box( 'commentsdiv',               'post', 'normal' ); // comments
	remove_meta_box( 'authordiv',                 'post', 'normal' ); // author
	remove_meta_box( 'trackbacksdiv',             'post', 'normal' ); // trackbacks
	remove_meta_box( 'revisionsdiv',              'post', 'normal' ); // revisions
}
add_action( 'admin_menu', 'tasty_remove_genesis_metaboxes', 50 );