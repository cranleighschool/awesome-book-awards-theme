<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 12/09/2017
 * Time: 13:52
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;

use CranleighSchool\AwesomeBookAwardsTheme\Widgets\RelatedPosts;
use WP_Query;

class Book extends BaseType {

	protected static $post_type_key = "book";

	public function setup() {

		$this->setIcon('dashicons-book-alt');
		$this->setOptions(['rewrite' => ['slug' => 'book']]);
		$this->setLabels(["featured_image" => "Book Cover"]);
		$this->addSupportFor("page-attributes");
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
			$author_id = get_post_meta($post_id, 'author', true);
			if ($author_id) {
				echo '<a href="' . get_edit_post_link( $author_id ) . '">' . get_the_title( $author_id ) . '</a>';
			} else {
				echo 'Not Set';
			}
		});

		$this->post_type->columns()->hide(['date']);

		$this->post_type->columns()->sortable([
			'score' => ['score', true],
		]);

		$this->post_type->taxonomy('awesome-year');

	}

	public static function getNews($title="Latest News", $book_id=null) {
		if ($book_id===null) {
			$book_id = get_the_ID();
		}

		global $post;

		$args = [
			"post_type" => 'post',
			"posts_per_page" => -1,
			"meta_query" => [
				[
					"key" => "related_books",
					"value" => $book_id,
					"compare" => "LIKE"
				]
			]
		];
		if ($title==="args") {
			return $args;
		}

		$query = new WP_Query($args);
		if ($query->have_posts()):
			echo '<div class="widget box-shadow">';
			echo "<h3>".$title."</h3>";
			echo "<ul>";
			while($query->have_posts()): $query->the_post();
				echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
			endwhile;
			echo "</ul>";
			echo "</div>";
		endif;
		wp_reset_query();
		wp_reset_postdata();

		return true;

	}

}