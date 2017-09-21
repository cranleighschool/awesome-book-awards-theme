<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 09:29
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;

use WP_Query;

class Author extends BaseType {

	protected static $post_type_key = "book_author";

	public function setup() {
		global $wp_rewrite;
		$wp_rewrite->author_base = "hiddenauthors";
		$this->setIcon('dashicons-admin-users');
		$this->setOptions(['rewrite' => ['slug' => 'author']]);
		$this->setLabels(["featured_image" => "Author Headshot", 'name' => 'Authors', 'menu_name' => 'Authors', 'all_items' => 'Authors']);
	}

	public function render() {
		$this->post_type->columns()->hide(['author', 'date']);
		$this->post_type->taxonomy('awesome-year');

		$this->post_type->columns()->add([
			'mugshot' => __("Mugshot"),
		]);

		$this->post_type->columns()->populate('mugshot', function($column, $post_id) {
			echo get_the_post_thumbnail($post_id, [100,100]);
		});
	}

	public static function getBooks() {
		$args = [
			"post_type" => Book::getPostTypeKey(),
			"posts_per_page" => -1,
			"meta_query" => [
				[
					"key" => "author",
					"value" => get_the_ID(),
					"compare" => "="
				]
			]
		];
		$query = new WP_Query($args);

		if ($query->have_posts()):
			echo "<div class='widget box-shadow'>";
			echo "<h3>Books</h3>";
			//echo "<ul>";
			echo '<div class="row">';
			while($query->have_posts()): $query->the_post();
			//	echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
				echo '<div class="col-4">';
				echo '<a href="'.get_permalink().'">';
				echo get_the_post_thumbnail(get_the_ID(), 'book-cover', ["class"=>"img-responsive"]);
				echo '</a>';
				echo '</div>';
			endwhile;
			echo "</div>";
			//echo "</ul>";
			echo "</div>";
		endif;
		wp_reset_query();
		wp_reset_postdata();
	}

	public static function getNews() {
		global $post;
		$args = [
			"post_type" => 'post',
			"posts_per_page" => -1,
			"tax_query" => [
				[
					"taxonomy" => "post_tag",
					"terms" => $post->post_name,
					"field" => "slug"
				]
			]
		];
		$query = new WP_Query($args);
		if ($query->have_posts()):
			echo '<div class="widget box-shadow">';
			echo "<h3>Latest News</h3>";
			echo "<ul>";
			while($query->have_posts()): $query->the_post();
				echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
			endwhile;
			echo "</ul>";
			echo "</div>";
		endif;
		wp_reset_query();
		wp_reset_postdata();

	}

}