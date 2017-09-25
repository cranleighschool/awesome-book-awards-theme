<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 09:29
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;


class Partner extends BaseType {

	static public $post_type_key = "partner";

	public function setup() {
		$this->setIcon('dashicons-thumbs-up');
		$this->setLabels(["featured_image" => "Partner Logo", "set_featured_image" => "Set Partner Logo"]);
	}

	public function render() {
		$this->post_type->columns()->hide(['author', 'date']);
		$this->post_type->taxonomy('awesome-year');
		$this->post_type->taxonomy('sponsor-type');
	}

}