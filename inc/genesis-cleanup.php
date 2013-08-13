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

// Set default layout
genesis_set_default_layout( 'content-sidebar' );

// Remove Edit link 
add_filter( 'genesis_edit_post_link', '__return_false' );

// Remove unused page layouts
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-content'         );

// Remove secondary/header sidebars
unregister_sidebar( 'sidebar-alt'  );
unregister_sidebar( 'header-right' );

// Remove menus
remove_theme_support( 'genesis-menus' );

// Remove header description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' ); 

// Remove post info
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

/**
 * Remove Genesis metaboxes
 *
 * @since 2.0.0
 */
function tasty_remove_genesis__post_metaboxes(){
	remove_meta_box( 'genesis_inpost_seo_box',     'post', 'normal' ); // Genesis seo
	remove_meta_box( 'genesis_inpost_layout_box',  'post', 'normal' ); // Genesis layout
	remove_meta_box( 'genesis_inpost_scripts_box', 'post', 'normal' ); // Genesis scripts
}
add_action( 'admin_menu', 'tasty_remove_genesis__post_metaboxes', 50 );

/**
 * Remove Genesis user settings
 *
 * @since 2.0.0
 */
function tasty_remove_user_profile_fields() {
	remove_action( 'show_user_profile', 'genesis_user_options_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
	remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'show_user_profile', 'genesis_user_seo_fields'     );
	remove_action( 'edit_user_profile', 'genesis_user_seo_fields'     );
	remove_action( 'show_user_profile', 'genesis_user_layout_fields'  );
	remove_action( 'edit_user_profile', 'genesis_user_layout_fields'  );
}
add_action( 'admin_init', 'tasty_remove_user_profile_fields' );

/**
 * Remove Genesis widgets.
 *
 * @since 2.0.0
 */
function tasty_remove_genesis_widgets() {
    unregister_widget( 'Genesis_Featured_Page'       );
    unregister_widget( 'Genesis_Featured_Post'       );
    unregister_widget( 'Genesis_User_Profile_Widget' );
}
add_action( 'widgets_init', 'tasty_remove_genesis_widgets', 20 );

/**
 * Remove Genesis theme settings metaboxes.
 *
 * @since 2.0.0
 * @param string $_genesis_theme_settings_pagehook
 */
function tasty_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
	//remove_meta_box( 'genesis-theme-settings-feeds',      $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-header',     $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-nav',        $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-comments',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-posts',      $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-blogpage',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );
}
add_action( 'genesis_theme_settings_metaboxes', 'tasty_remove_genesis_metaboxes' );