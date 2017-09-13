<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 12/09/2017
 * Time: 13:52
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;

use WP_Query;

class Book extends BaseType {

	protected $post_type_key = "book";

	public function setup() {

		$this->setIcon('dashicons-book-alt');
		$this->setOptions(['rewrite' => ['slug' => 'book']]);
		$this->setLabels(["featured_image" => "Book Cover"]);

	}

	public function render() {

		$this->post_type->columns()->add([
			'score' => __("Book's Score"),
			'book_author' => __("Book's Author")
		]);
		$this->post_type->columns()->populate('score', function($column, $post_id) {
			echo get_post_meta($post_id, 'score', true); // . '/10';
		});
		$this->post_type->columns()->populate('book_author', function($column, $post_id) {
			echo '<a href="'.get_edit_post_link(get_post_meta($post_id, 'author', true)).'">'.get_the_title(get_post_meta($post_id, 'author', true)).'</a>';
		});
		$this->post_type->columns()->hide(['date']);
		$this->post_type->columns()->sortable([
			'score' => ['score', true],
		]);

		$this->post_type->taxonomy('year');

	}

	public function getPosts() {
		$args = [
			"posts_per_page" => -1,
			"post_type" => $this->post_type_key,
			"orderby" => [
				"menu_order" => "ASC",
				"title" => "ASC"
			]

		];

		return new WP_Query($args);

	}
}