<?php
/**
 * Tasty child theme.
 *
 * @package      TastyChildTHeme
 * @since        1.0.0
 * @copyright    Copyright (c) 2013, Jared Atchison
 * @author       Jared Atchison <contact@jaredatchison.com>
 * @license      GPL-2.0+
 */

/**
 * Theme setup.
 * 
 * Attach all of the site-wide functions to the correct hooks and filters. All 
 * the functions themselves are defined below this setup function.
 *
 * @since 2.0.0
 */
function tasty_child_theme_setup() {

	define( 'CHILD_THEME_NAME',    'Tasty Child Theme'                  );
	define( 'CHILD_THEME_URL',     'https://github.com/jaredatch/Tasty' );
	define( 'CHILD_THEME_VERSION', '2.0.0'                              );

	// Translations
	load_child_theme_textdomain( 'ja-tasty-child', apply_filters( 'tasty_child_theme_textdomain', get_stylesheet_directory() . '/languages', 'ja-tasty-child' ) );

	// Includes
	require_once( get_stylesheet_directory() . '/inc/wordpress-cleanup.php' ); // Remove some WordPress features
	require_once( get_stylesheet_directory() . '/inc/genesis-cleanup.php'   ); // Remove some Genesis features
	require_once( get_stylesheet_directory() . '/inc/admin.php'             ); // Admin-side customizations
	require_once( get_stylesheet_directory() . '/inc/general.php'           ); // General functions and actions
	require_once( get_stylesheet_directory() . '/inc/autocomplete.php'      ); // Tag autocomplete
	require_once( get_stylesheet_directory() . '/inc/bookmark-this.php'     ); // Custom bookmarklet

	// Theme Supports
	add_theme_support( 'html5' );
	add_theme_support( 'genesis-responsive-viewport' );
	add_theme_support( 'genesis-structural-wraps', array( 'header', 'site-inner', 'footer' ) );

}
add_action( 'genesis_setup', 'tasty_child_theme_setup', 15 );