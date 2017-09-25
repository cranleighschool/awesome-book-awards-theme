<?php
/**
 * Theme basic setup.
 *
 * @package understrap
 */

require get_template_directory() . '/inc/theme-settings.php';

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}



if ( ! function_exists( 'custom_excerpt_more' ) ) {
	/**
	 * Removes the ... from the excerpt read more link
	 *
	 * @param string $more The excerpt.
	 *
	 * @return string
	 */
	function custom_excerpt_more( $more ) {
		return '';
	}
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

if ( ! function_exists( 'all_excerpts_get_more_link' ) ) {
	/**
	 * Adds a custom read more link to all excerpts, manually or automatically generated
	 *
	 * @param string $post_excerpt Posts's excerpt.
	 *
	 * @return string
	 */
	function all_excerpts_get_more_link( $post_excerpt ) {

		return $post_excerpt . ' [...]<p>'.\CranleighSchool\AwesomeBookAwardsTheme\Setup::modify_read_more_link().'</p>';
//		<p><a class="btn btn-secondary understrap-read-more-link" href="' . get_permalink( get_the_ID() ) . '">' . __( 'Read More...',
//		'understrap' ) . '</a></p>';
	}
}
add_filter( 'wp_trim_excerpt', 'all_excerpts_get_more_link' );

