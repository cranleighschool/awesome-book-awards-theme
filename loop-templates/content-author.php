<?php
/**
 * Single post partial template.
 *
 * @package understrap
 */
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">
		<div class="entry-meta">

			<?php
			$years = wp_get_post_terms(get_the_ID(), 'awesome-year');
			if ($years):
				echo "Shortlisted: ";
				$terms_list = get_the_term_list( get_the_ID(), 'awesome-year', '', ', ', '' );
				echo "<strong>".strip_tags( $terms_list )."</strong>";

			endif;
			?>
		</div><!-- .entry-meta -->
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->


	<div class="entry-content">
		<div class="row">
			<div class="col-md-8">
				<h2 class="sr-only">Biography</h2>
				<?php the_content(); ?>
			</div>

			<div class="col-md-4 pull-left">
				<?php if (has_post_thumbnail()): ?>
				<div class="author-mugshot-wrapper">
					<?php the_post_thumbnail( 'book-cover' ); ?>
				</div>
				<?php endif; ?>
				<?php
				$news = \CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Author::getNews();

				$books = \CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Author::getBooks();

				?>
			</div>




		</div>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
			'after'  => '</div>',
		) );
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php understrap_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	<section id="relatedAuthors">
		<?php \CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Author::getRelatedAuthors(get_the_ID()); ?>
	</section>
</article><!-- #post-## -->
