<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 12/09/2017
 * Time: 13:53
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;

use PostTypes\PostType;
use WP_Error;
use YeEasyAdminNotices\V1\AdminNotice;

abstract class BaseType {

	protected $options = [
		"has_archive" => true,
		"supports"    => [
			"thumbnail",
			"title",
			"editor"
		]
	];
	protected $labels = [];

	protected $icon;

	protected $post_type = false;

	public function init() {

		$this->setup();

		if(!is_wp_error($this->setCustomPostType())) {
			$this->render();
		}

	}

	abstract function setup();

	protected function setCustomPostType() {

		if (empty($this->post_type_key) || $this->post_type_key===false) {
			$error_message = '<code>$post_type_key</code>' . " has not been set for <strong>".get_called_class()."</strong>";
			AdminNotice::create()->error($error_message)->show();
			return new WP_Error( '400', $error_message);
		}

		$this->post_type = new PostType( $this->post_type_key, $this->options, $this->labels );
		$this->post_type->icon($this->icon);

		return $this->post_type;
	}

	abstract function render();

	protected function addSupportFor($value) {
		$this->options['supports'][] = $value;
	}

	/**
	 * @param string $icon
	 */
	protected function setIcon( string $icon ) {

		$this->icon = $icon;
	}

	/**
	 * @param array $labels
	 */
	protected function setLabels( array $labels ) {

		$this->labels = array_merge( $this->labels, $labels );
	}

	/**
	 * @param array $options
	 */
	protected function setOptions( array $options ) {

		$this->options = array_merge( $this->options, $options );
	}

}