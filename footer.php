<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

$the_theme = wp_get_theme();
$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_sidebar( 'footerfull' ); ?>

<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">
			<div class="col-md-8">
				<div class="site-info">
					<?php printf( //WPCS: XSS ok.
						( 'The Awesome Book Awards are an initiative by %1$s. <br />For more information please contact %2$s.'), '<a href="'.esc_url( 'https://www.cranprep.org/' ).'">Cranleigh Preparatory School</a>', '<a href="mailto:ekkr@cranprep.org">Emma Reid</a>'); ?></div>
			</div>
			
			<div class="col-md-4">
				<?php the_custom_logo(); ?>
			</div>


			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<div class="site-info">
						
					</div><!-- .site-info -->

				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

