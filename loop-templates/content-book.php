<?php
/**
 * Single post partial template.
 *
 * @package understrap
 */

$author = get_post(get_post_meta(get_the_ID(), 'author', true));
$author->image = get_the_post_thumbnail($author->ID, 'book-cover');
					
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->


	<div class="entry-content">
		<div class="row">
			<div class="col-md-8">
				<?php the_content(); ?>
			</div>
			<div class="col-md-4">
				<div class="book-cover-wrapper" style="border:1px solid black;padding:2px;margin-bottom:2px;">
					<?php the_post_thumbnail( 'book-cover' ); ?>
				</div>
				<div class="pea-green-link-wrapper">
					<?php
						$link = get_post_meta( get_the_ID(), 'peagreenlink', true );
						$image = wp_get_attachment_image( 37, 'thumbnail', true, ['style'=>"width:35px"]);
						echo ($image);
					?>
					<a href="<?php echo $link; ?>">Buy with Pea Green Boat Books</a>
				</div>
			</div>
			<div class="col-md-12">
				<h2>About The Author</h2>
				<div class="row" style="background: white;">
					<div class="col-md-4">
						<?php echo $author->image; ?>
					</div>
						
					<div class="col-md-8">
						<h3 style="text-decoration: underline;"><?php echo $author->post_title; ?></h3>
						<?php echo wpautop( $author->post_content ); ?>
					</div>
				</div>
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

</article><!-- #post-## -->
