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
						( 'The Awesome Book Awards are an initiative by %1$s. <br />For more information please contact %2$s.'), '<a href="'.esc_url( 'https://www.cranprep.org/' ).'">Cranleigh Preparatory School</a>', '<a href="mailto:info@awesomebookawards.com">Porsche Bailey</a>'); ?></div>
			</div>

			<div class="col-md-4">
				<?php $custom_logo_id = get_theme_mod('custom_logo');
				$image = wp_get_attachment_image_src($custom_logo_id, 'full');
				echo '<img src="'.$image[0].'" class="img-fluid" />'; ?>
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
<script>
	var $buoop = {vs:{i:10,f:-4,o:-4,s:9,c:-4},reminder:0,text: "Our apologies! {brow_name} is considered an out of date web browser and not fully compatible with The Awesome Book Awards website. <br />Your experience will be hindered if you continue using this browser. Please <a{up_but}>Update</a>.", unsecure:true,api:4};
	function $buo_f(){
		var e = document.createElement("script");
		e.src = "//browser-update.org/update.min.js";
		document.body.appendChild(e);
	};
	try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
	catch(e){window.attachEvent("onload", $buo_f)}
</script>
</body>

</html>

