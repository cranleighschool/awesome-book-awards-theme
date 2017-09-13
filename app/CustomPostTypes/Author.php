<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 09:29
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;


class Author extends BaseType {

	protected $post_type_key = "book_author";

	public function setup() {
		global $wp_rewrite;
		$wp_rewrite->author_base = "hiddenauthors";
		$this->setIcon('dashicons-admin-users');
		$this->setOptions(['rewrite' => ['slug' => 'author']]);
		$this->setLabels(["featured_image" => "Author Headshot", 'name' => 'Authors', 'menu_name' => 'Authors', 'all_items' => 'Authors']);
	}

	public function render() {
		$this->post_type->columns()->hide(['author', 'date']);
		$this->post_type->taxonomy('year');
	}

}