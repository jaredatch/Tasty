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
 * Enqueue the javascript for autocomplete
 *
 * @since 2.0.0
 */
function tasty_add_jquery() {
	wp_enqueue_script( 'jquery'         );
	wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'suggest'        );
}
add_action( 'wp_enqueue_scripts', 'tasty_add_jquery' );

/**
 * Add tag auto complete
 *
 * @since 2.0.0
 */
function tasty_add_autosuggest() {
	?>
    <script type="text/javascript">
    function setSuggest(id) {
        jQuery('#' + id).suggest("<?php echo admin_url( '/admin-ajax.php?action=ajax-tag-search&tax=post_tag' ); ?>", { delay: 500, minchars: 2, multiple: false });
    }
    </script>
	<?php
}
add_action( 'wp_head', 'tasty_add_autosuggest' );

// Enables autocompletion for non-logged-in users
add_action( 'wp_ajax_nopriv_ajax-tag-search', 'tasty_add_autosuggest_links_callback' );
add_action( 'wp_ajax_ajax-tag-search', 'tasty_add_autosuggest_links_callback' );

/**
 * Modified from admin-ajax.php
 *
 * @since  2.0.0
 * @global $wpdb
 */
function tasty_add_autosuggest_links_callback() {

	global $wpdb;

	if ( isset( $_GET['tax'] ) ) {
		$taxonomy = sanitize_key( $_GET['tax'] );
		$tax = get_taxonomy( $taxonomy );
		if ( ! $tax )
			die( '0' );
	} else {
		die('0');
	}

	$s = stripslashes( $_GET['q'] );

	if ( false !== strpos( $s, ',' ) ) {
		$s = explode( ',', $s );
		$s = $s[count( $s ) - 1];
	}
	$s = trim( $s );
	if ( strlen( $s ) < 2 )
		die; // require 2 chars for matching

	$results = $wpdb->get_col( $wpdb->prepare( "SELECT t.name FROM $wpdb->term_taxonomy AS tt INNER JOIN $wpdb->terms AS t ON tt.term_id = t.term_id WHERE tt.taxonomy = %s AND t.name LIKE (%s)", $taxonomy, '%' . like_escape( $s ) . '%' ) );

	echo join( $results, "\n" );

}