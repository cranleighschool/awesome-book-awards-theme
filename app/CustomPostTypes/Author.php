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
		$this->addSupportFor("excerpt");
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

	public static function getTwitterTimeline($num = 5, $id=null) {
		if ($id===null) {
			$id = get_the_ID();
		}
		$twitter_screen_name = get_post_meta($id, 'author_twitter_handle', true);
		if (!empty($twitter_screen_name))
			return '<div class="widget box-shadow no-padding"><a class="twitter-timeline" data-show-replies="false" data-chrome="nofooter" data-tweet-limit="5" data-link-color="#8CB7E8" href="https://twitter.com/'.$twitter_screen_name.'">Tweets by @'.$twitter_screen_name.'</a><small style="padding:10px;display:block;font-style: italic">Awesome Book Awards are not responsible for content tweeted by Authors.</small></div>';
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
	public static function getRelatedAuthors($id) {
		if (get_post_type($id)==='post') {
			foreach (get_post_meta($id, 'related_authors', true) as $author_id):
				echo self::signpost($author_id);
			endforeach;
		} elseif (get_post_type($id)===self::getPostTypeKey()) {
			$years = wp_get_post_terms($id, 'awesome-year');
			$tax_query = [
				"relation" => "OR"
			];
			foreach ($years as $year):
				array_push($tax_query, [
					"taxonomy" => "awesome-year",
					"field" => "slug",
					"terms" => $year->slug
				]);
			endforeach;

			$args = [
				"post_type" => self::getPostTypeKey(),
				"posts_per_page" => 4,
				"post__not_in" => [$id],
				"tax_query" => $tax_query
			];
			$query = new WP_Query($args);
			if ($query->have_posts()) {

				echo "<br /><h3>Other Authors</h3>";
				echo '<div id="related-authors">';
				echo '<div class="row">';
				while ( $query->have_posts() ): $query->the_post();
					echo '<div class="col-md-3 col-sm-6 text-center">';
					echo '<div class="widget box-shadow">';
					echo '<a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), 'author-mugshot');
					echo '</a>';
					echo '</div>';
					echo '<h4 class="text-center"><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
					echo '</div>';
				endwhile;
				echo '</div>';
				echo '</div>';
			}
			wp_reset_postdata();
			wp_reset_query();
		}
	}


	public static function signpost($author_id, string $text=null) {
		$author = get_post($author_id);
		if ($text === null) {
			$text = "More About ".$author->post_title;
		}
		if ($text === "Read More...") {
			$style = "signpost pull-right";
		} else {
			$style = "signpost";
		}

		return '<a class="'.$style.'" href="'.get_permalink($author).'">'.$text.' <i class="fa fa-fw fa-chevron-right"></i></a>';
	}
	public static function getNews($author_id=null) {
		if ($author_id===null) {
			$author_id = get_the_ID();
		}
		global $post;
		$args = [
			"post_type" => 'post',
			"posts_per_page" => -1,
			"meta_query" => [
				[
					"key" => "related_authors",
					"value" => $author_id,
					"compare" => "LIKE"
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
		return true;

	}

	public static function wp_first_paragraph_excerpt( $id=null ) {
		// Set $id to the current post by default
		if( !$id ) {
			global $post;
			$id = get_the_id();
		}

		// Get the post content
		$content = get_post_field( 'post_content', $id );
		$content = apply_filters( 'the_content', strip_shortcodes( $content ) );

		// Remove all tags, except paragraphs
		$excerpt = strip_tags( $content, '<p></p>' );

		// Remove empty paragraph tags
		$excerpt = force_balance_tags( $excerpt );
		$excerpt = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $excerpt );
		$excerpt = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $excerpt );

		// Get the first paragraph
		$excerpt = substr( $excerpt, 0, strpos( $excerpt, '</p>' ) + 4 );

		// Remove remaining paragraph tags
		$excerpt = strip_tags( $excerpt );

		return $excerpt."<br />".self::signpost($id, "Read More...");
	}

}