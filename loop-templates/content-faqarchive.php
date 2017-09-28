<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package understrap
 */

?>

<article <?php post_class('archive-article'); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php if ( 'post' == get_post_type() ) : ?>

			<div class="entry-meta">
				<?php understrap_posted_on(); ?>
			</div><!-- .entry-meta -->

		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
		'</a></h2>' ); ?>

	</header><!-- .entry-header -->


	<div class="entry-content">
		<?php echo get_the_post_thumbnail( $post->ID, 'author-mugshot' ); ?>

		<?php
		the_excerpt();
		?>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
			'after'  => '</div>',
		) );
		?>

		<div class="clear clearfix">&nbsp;</div>
	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->