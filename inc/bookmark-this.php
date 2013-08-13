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
 *  This a modified version of Press This Reloaded
 *
 * @since   2.0.0
 * @link    http://scribu.net/wordpress/press-this-reloaded
 * @package TastyChildTHeme
 */
class Tasty_Bookmark_This_Reloaded {

	private static $title;
	private static $content;

	function init() {
		add_filter( 'shortcut_link',          array(  __CLASS__, 'shortcut_link' ) );
		add_filter( 'redirect_post_location', array( __CLASS__, 'redirect'       ) );

		if ( isset( $_GET['u'] ) ) {
			add_action( 'load-post-new.php', array(  __CLASS__, 'load' ) );
			add_action( 'load-post.php',     array(  __CLASS__, 'load' ) );
		}
	}

	function shortcut_link( $link ) {
		$link = str_replace( 'press-this.php', 'post-new.php', $link );
		$link = str_replace( 'width=720', 'width=880', $link );
		$link = str_replace( 'height=570', 'height=320', $link );

		return $link;
	}

	function redirect( $location ) {
		$referrer = wp_get_referer();

		if ( false !== strpos( $referrer, '?u=' ) || false !== strpos( $referrer, '&u=' ) )
			$location = add_query_arg( 'u', 1, $location );

		return $location;
	}

	function load() {
		$title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';

		$url = isset( $_GET['u'] ) ? esc_url( $_GET['u'] ) : '';
		$url = wp_kses( urldecode( $url ), null );

		$selection = '';
		if ( !empty( $_GET['s'] ) ) {
			$selection = str_replace( '&apos;', "'", stripslashes( $_GET['s'] ) );
			$selection = trim( htmlspecialchars( html_entity_decode( $selection, ENT_QUOTES ) ) );
		}

		self::$content = '';
		if ( !empty( $selection ) ) {
			self::$content  = "$selection\n";
		} else {
			self::$content  = '';
		}

		self::$title = $title;

		// Call up custom CSS
		function style() {
			wp_enqueue_style( 'tasty-bookmarkthis' );
		}
		add_action( 'admin_enqueue_scripts', 'style' );

		// Filters
		add_filter( 'default_title',   array( __CLASS__, 'default_title'   ) );
		add_filter( 'default_content', array( __CLASS__, 'default_content' ) );
		add_filter( 'show_admin_bar',  false                                 );
	}

	function default_title() {
		return self::$title;
	}

	function default_content() {
		return self::$content;
	}
}

Tasty_Bookmark_This_Reloaded::init();