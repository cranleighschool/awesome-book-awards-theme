<?php
	/**
	 * Created by PhpStorm.
	 * User: fredbradley
	 * Date: 26/09/2017
	 * Time: 10:02
	 */

	namespace CranleighSchool\AwesomeBookAwardsTheme\Shortcodes;

	use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\FAQ;
	use WP_Query;

	/**
	 * Class FAQs
	 *
	 * @package CranleighSchool\AwesomeBookAwardsTheme\Shortcodes
	 */
	class FAQs extends BaseShortcode {

		/**
		 * @var string
		 */
		protected $tag = 'faqs';

		/**
		 * @var array
		 */
		protected $atts = [];


		/**
		 * @var WP_Query
		 */
		protected $query;


		/**
		 * @param        $atts
		 * @param string|null $content
		 *
		 * @return string
		 */
		public function render( $atts, string $content = NULL ) {
			$this->atts = shortcode_atts( array(), $atts );

			$args = [
					"post_type"      => FAQ::getPostTypeKey(),
					"posts_per_page" => - 1,
					"orderby"        => "menu_order",
					"order"          => "ASC"
			];

			$this->query = new WP_Query( $args );

			return $this->showFAQs();

		}

		/**
		 * @return string
		 */
		private function showFAQs() {
			$parent_id = "faqs-accordion";
			$output    = '<div id="' . $parent_id . '" role="tablist" aria-multiselectable="true">';

			while ( $this->query->have_posts() ): $this->query->the_post();
				$output .= $this->eachFAQ( "faq-" . get_the_ID(), "collapse-faq-" . get_the_ID() );
			endwhile;

			$output .= '</div>';

			return $output;
		}

		/**
		 * @param $id
		 * @param $href
		 *
		 * @return false|string
		 */
		private function eachFAQ( $id, $href ) {
			global $post;
			ob_flush();
			?>
			<div class="card">
				<div class="card-header" role="tab" id="<?php echo $id; ?>">
					<h5 class="mb-0">
						<a data-toggle="collapse" class="collapsed"
						   href="#<?php echo $href; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $href; ?>">
							<?php echo get_the_title(); ?> <i class="fa fa-fw fa-chevron-right"></i>
						</a>
					</h5>
				</div>
				<div id="<?php echo $href; ?>" class="collapse" role="tabpanel"
					 aria-labelledby="<?php echo $href . "-heading"; ?>">
					<div class="card-body">
						<?php echo wpautop( $post->post_content ); ?>
					</div>
				</div>
			</div>
			<?php
			$contents = ob_get_contents();
			ob_clean();

			return $contents;
		}
	}
