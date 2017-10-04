<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 04/10/2017
 * Time: 09:28
 */
namespace CranleighSchool\AwesomeBookAwardsTheme\Widgets;

use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Author;
use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Book;
use CranleighSchool\AwesomeBookAwardsTheme\Setup;
use WP_Widget;
use WP_Query;

class RelatedPosts extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname' => 'relatedposts',
			'description' => 'Cranleigh Related Posts'
		);
		parent::__construct('cranleigh-related-posts', 'Cranleigh Related Posts Widget', $widget_ops);
	}

	function widget($args, $instance) {
		if (!is_single()) {
			return false;
		}
		if (get_post_type() == 'book') {
			$post_args = Book::getNews("args");
		}
		if (get_post_type() == 'book_author') {
			$post_args = Author::getNews('args');
		}
		if (get_post_type() == 'post') {
			return false;
		}

		$a = shortcode_atts(
			array(
				"post_type" => "post",
				"posts_per_page"=>5
			),
			$instance
		);

		if (!isset($post_args)) {
			$post_args = [
				"post_type"      => $a[ 'post_type' ],
				"posts_per_page" => $a[ 'posts_per_page' ],
			];
		}

		if (
			isset($instance['taxonomy']) && ( (isset($instance['include_terms']) && !empty($instance['include_terms'])) || (isset($instance['exclude_terms']) && !empty($instance['exclude_terms']) ) )) {
			$post_args['tax_query'] = [
				"relation" => "OR",
				[
					"taxonomy" => $instance['taxonomy'],
					'field' => 'slug',
					'terms' => explode(",", $instance['include_terms'])
				],
				[
					"taxonomy" => $instance['taxonomy'],
					'field' => 'slug',
					'terms' => explode(",", $instance['exclude_terms']),
					'operator' => 'NOT IN'
				],
			];
		}

		$query = new WP_Query($post_args);

		if ($query->post_count < 1) {
			echo '<!-- HIDE BECAUSE THERE IS NO POSTS TO DISPLAY -->';
			return false;
		}
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}


		echo '<div class="recent-posts">';
		echo '<ul class="list-unstyled post-list">';
		while($query->have_posts()): $query->the_post();

			if (get_permalink() == Setup::currentUrl(true))
				continue;
			?>
			<li>
				<div class="row">
					<div class="col-4">
						<a href="<?php echo get_permalink(); ?>">
							<?php
							if (has_post_thumbnail()) {
								the_post_thumbnail( 'thumbnail' );
							} else {
								$attachment_id = get_theme_mod("default_featured_image");
								echo wp_get_attachment_image($attachment_id, "thumbnail");
							}
							?>
						</a>
					</div>
					<div class="col-8">
						<p>
							<a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>
						</p>
						<p><small><?php echo $this->getExcerpt(get_the_content(), 70); ?></small></p>
					</div>
				</div>
			</li>
		<?php endwhile;
		wp_reset_postdata();
		echo "</ul>";
		echo "</div>";


		echo $args['after_widget'];
	}

	function getExcerpt($content, $chars=50) {
		$content = strip_tags(apply_filters('the_content', $content));

		if (count_chars($content) > $chars) {
			$content = substr($content, 0, $chars)."...";
		}
		return $content;
	}
	function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['post_type'] = (!empty($new_instance['post_type'])) ? strip_tags($new_instance['post_type']):'';
		$instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? strip_tags($new_instance['posts_per_page']):'';
		$instance['taxonomy'] = (!empty($new_instance['taxonomy'])) ? strip_tags($new_instance['taxonomy']):'';
		$instance['include_terms'] = (!empty($new_instance['include_terms'])) ? strip_tags($new_instance['include_terms']):'';
		$instance['exclude_terms'] = (!empty($new_instance['exclude_terms'])) ? strip_tags($new_instance['exclude_terms']):'';

		return $instance;
	}

	function form($instance) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Posts', 'cranleigh-2016' );
		$post_type = ! empty( $instance['post_type'] ) ? $instance['post_type'] : __( 'posts', 'cranleigh-2016' );
		$posts_per_page = ! empty( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : 5;
		$taxonomy =  ! empty( $instance['taxonomy'] ) ? $instance['taxonomy'] : __( '', 'cranleigh-2016' );
		$include_terms = ! empty( $instance['include_terms'] ) ? $instance['include_terms'] : '';
		$exclude_terms = ! empty( $instance['exclude_terms'] ) ? $instance['exclude_terms'] : '';

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php _e( esc_attr( 'Post Type:' ) ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>">
				<?php foreach (get_post_types(null, 'objects') as $type):
					if ($type->name==$post_type):
						echo "<option selected=\"selected\" value=\"".$type->name."\">".$type->label."</option>";
					else:
						echo "<option value=\"".$type->name."\">".$type->label."</option>";
					endif;
				endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php _e( esc_attr( 'Posts Per Page:' ) ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" placeholder="5" value="<?php echo esc_attr( $posts_per_page ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php _e( esc_attr( 'Taxonomy:' ) ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>">
				<option selected="selected" value="">None</option>
				<?php foreach (get_taxonomies(null, 'objects') as $type):
					if ($type->name==$taxonomy):
						echo "<option selected=\"selected\" value=\"".$type->name."\">".$type->label."</option>";
					else:
						echo "<option value=\"".$type->name."\">".$type->label."</option>";
					endif;
				endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'include_terms' ) ); ?>"><?php _e( esc_attr( 'Included Term Slugs:' ) ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'include_terms' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'include_terms' ) ); ?>" type="text" value="<?php echo esc_attr( $include_terms ); ?>">
			<small>Comma separated list of slugs to look for</small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'exclude_terms' ) ); ?>"><?php _e( esc_attr( 'Excluded Term Slugs:' ) ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'exclude_terms' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'exclude_terms' ) ); ?>" type="text" value="<?php echo esc_attr( $exclude_terms ); ?>">
			<small>Comma separated list of slugs to look for</small>
		</p>
		<?php
	}

}


