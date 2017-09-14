<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 14/09/2017
 * Time: 11:56
 */

namespace CranleighSchool\AwesomeBookAwardsTheme;

class GetPostMeta {

	private $post_id;

	public function __construct($post_id) {
		$this->post_id = $post_id;
	}

	public function __get($value) {
		return get_post_meta($this->post_id, $value, true);
	}
}