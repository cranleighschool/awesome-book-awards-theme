<?php

	$years = wp_get_post_terms( get_the_ID(), 'awesome-year' );

$query = [];
	foreach ($years as $year):
		$query[] = $year->slug;
	endforeach;

	$tax_query = [
		"relation" => "OR",
		[
			'taxonomy' => 'awesome-year',
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
		<h2>Other Books from <?php echo $years[0]->name; ?></h2>
	</div>
	<?php
	$book = new \CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Book();

	$relatedPosts = $book->relatedPosts(get_the_ID(), 4);
//
	while($relatedPosts->have_posts()): $relatedPosts->the_post();
		echo "<div class='col-md-3'>".get_the_title()."</div>";
	endwhile;
	?>
</div>