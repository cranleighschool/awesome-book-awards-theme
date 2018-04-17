<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 06/12/2017
 * Time: 08:42
 */

namespace CranleighSchool\AwesomeBookAwardsTheme;


class HideAttachmentPages {
	public function __construct() {
		add_action( 'template_redirect', array($this,'redirect_attachment_page'));
		add_filter('attachment_link', array($this, 'cleanup_attachment_link'));
		add_filter( 'rewrite_rules_array', array($this, 'cleanup_default_rewrite_rules' ));

	}

	public function redirect_attachment_page() {
		if (is_attachment()) {
			global $post;
			if ( $post && $post->post_parent ) {
				wp_redirect( esc_url( get_permalink( $post->post_parent ) ), 301 );
				exit;
			} else {
				wp_redirect( esc_url( home_url( '/' ) ), 301 );
				exit;
			}
		}
	}

	public function cleanup_attachment_link($link) {
		return;
	}

	public function cleanup_default_rewrite_rules( $rules ) {
		foreach ( $rules as $regex => $query ) {
			if ( strpos( $regex, 'attachment' ) || strpos( $query, 'attachment' ) ) {
				unset( $rules[ $regex ] );
			}
		}

			return $rules;
	}

}