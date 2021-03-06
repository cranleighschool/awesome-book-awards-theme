<?php
/**
 * Single post partial template.
 *
 * @package understrap
 */
if (get_post_meta(get_the_ID(), 'author', true)) {
	$author = get_post(get_post_meta(get_the_ID(), 'author', true));
	$author->image = get_the_post_thumbnail($author->ID, 'author-mugshot', ['class'=>'author-mugshot-inline']);
} else {
	$author = new WP_Error("404", "Author Not Found");
}
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
				<?php the_content(); ?>
			</div>
			<div class="col-md-4">
				<div class="book-cover-wrapper">
					<?php the_post_thumbnail( 'book-cover' ); ?>
				</div>
				<?php
					if (get_post_meta(get_the_ID(), 'peagreenlink', true)): ?>
				<div class="pea-green-boat-link-wrapper widget no-padding">
					<a class="btn btn-peagreen btn-block" href="<?php echo get_post_meta(get_the_ID(), 'peagreenlink', true); ?>">Purchase Book</a>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php if (!is_wp_error($reviews)): ?>
		<div class="row ">
			<div class="col-md-12">
				<?php \CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Book::getReviews(); ?>
			</div>
		</div>
		<?php endif; ?>
		<?php if (!is_wp_error($author)): ?>
		<div class="row ">
			<div class="col-md-8">
				<div class="widget box-shadow">


					<?php echo $author->image; ?>
					<h2 class="sr-only">About The Author</h2>
					<h3><?php echo $author->post_title; ?></h3>
					<?php echo wpautop( \CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Author::wp_first_paragraph_excerpt($author->ID) ); ?>
					<div class="clear clearfix">&nbsp;</div>
				</div>
			</div>
			<div class="col-md-4">
				<?php \CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Book::getNews(); ?>
			</div>
		</div>
		<?php endif; ?>

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
