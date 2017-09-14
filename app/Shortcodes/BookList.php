<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 16:14
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\Shortcodes;


use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Book;

class BookList extends BaseShortcode {

	protected $tag = 'book-list';
	protected $atts = [];

	public function render($atts, $content=null) {
		$this->atts = shortcode_atts(array(
			'year' => null,
			'title' => "Awesome Book Awards %s Shortlist"
		), $atts);

		if ($this->atts['year'] !== null) {
			return $this->displayBooksByYear();
		} else {
			return 'FAIL';
		}

	}

	private function displayBooksByYear() {
		$year = $this->atts['year'];
		$books = new Book();
		$posts = $books->getPosts([
			"tax_query" => [
				[
					"taxonomy" => 'year',
					'field' => 'slug',
					'terms' => $year
				]
			]
		]);
		$taxonomy = get_term_by('slug', $year, 'year');

		ob_start();

		echo '<div class="row">';
		echo '<div class="col-md-12">';
		echo '<h3>'.sprintf($this->atts['title'], $taxonomy->name).'</h3>';
		echo '</div>';
		echo '<div class="col-md-1"></div>';

		while($posts->have_posts()): $posts->the_post();
			echo '<div class="col-md-2">';
			echo "<h4 class='sr-only'>".get_the_title()."</h4>";
			echo '<a href="'.get_permalink().'">';
			the_post_thumbnail('book-cover');
			echo '</a>';
			echo '</div>';
		endwhile;
		echo '<div class="col-md-1"></div></div>';

		wp_reset_postdata();
		wp_reset_query();

		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}