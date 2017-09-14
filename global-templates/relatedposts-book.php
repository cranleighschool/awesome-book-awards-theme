<?php

	$years = wp_get_post_terms( get_the_id(), 'year' );
	$query = [];
	foreach ($years as $year):
		$query[] = $year->slug;
	endforeach;

	$tax_query = [
		"relation" => "OR",
		[
			'taxonomy' => 'year',
			'field' => 'slug',
			'terms' => $query
		]
	];
	$wp_query = [
		"post_type" => 'book',
		"posts_per_page" => 4,
		//'tax_query' => $tax_query
	];
?>

<div class="row">
	<div class="col-md-12">
		<h2>Other Books from </h2>
	</div>
	<?php
//	$book = new \CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Book();

//	$relatedPosts = $book->relatedPosts(get_the_ID(), 4, $wp_query);


//	echo '<pre>';var_dump($relatedPosts);echo '</pre>';

	$query = new WP_Query($wp_query);
	while($query->have_posts()):
		$query->the_post();
		echo "<div class='col-md-3'>".get_the_title()."</div>";
	endwhile;
	?>
</div>