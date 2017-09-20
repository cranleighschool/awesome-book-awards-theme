<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 09:29
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;


class School extends BaseType {

	protected static $post_type_key = "school";

	public function setup() {
		$this->setIcon('dashicons-building');
		$this->setOptions(['supports'=>['title', 'thumbnail']]);
		$this->setLabels(['featured_image' => "School Logo"]);
	}

	public function render() {
		$this->post_type->taxonomy('awesome-year');
	}
}