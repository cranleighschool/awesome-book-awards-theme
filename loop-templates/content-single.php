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

			<?php understrap_posted_on(); ?>

		</div><!-- .entry-meta -->

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>


	</header><!-- .entry-header -->


	<div class="entry-content">

		<?php
		if (has_post_thumbnail()):
			echo '<div class="featured-image">'.get_the_post_thumbnail( get_the_ID(), 'book-cover')."</div>";
		endif; ?>

		<?php the_content(); ?>

		<div class="clear clearfix">&nbsp;</div>

		<?php
			\CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Author::getRelatedAuthors(get_the_ID());
		?>

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

</article><!-- #post-## -->
