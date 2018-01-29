<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 11/12/2017
 * Time: 08:51
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;


class Review extends BaseType {
	protected static $post_type_key = "review";

	public function setup() {
		$this->setIcon('dashicons-building');
		$this->setOptions(['supports'=>['title', 'thumbnail', 'editor'], "has_archive" => true]);
		$this->setLabels(['featured_image' => "Book Cover"]);

	}

	public function render() {
		//$this->post_type->taxonomy('awesome-year');
		$this->post_type->columns()->add([
			'book' => __('Book')
		]);

		$this->post_type->columns()->populate('book', function($column, $post_id) {
			$array = [];
			foreach (get_post_meta($post_id, 'related_books', true) as $book):
				$array[] = get_post($book)->post_title;
			endforeach;
			echo implode(", ", $array);
		});
	}
}
