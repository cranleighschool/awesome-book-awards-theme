<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 12/09/2017
 * Time: 11:42
 */

namespace CranleighSchool\AwesomeBookAwardsTheme;


use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Author;
use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Partner;
use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Review;

class MetaBoxes {
	public function __construct() {
		add_filter( 'rwmb_meta_boxes', array($this,'book') );
		add_filter( 'rwmb_meta_boxes', array($this, 'book_scoring') );
		add_filter( 'rwmb_meta_boxes', array($this, 'sponsors') );
		add_filter( 'rwmb_meta_boxes', array($this, 'authors_news') );
		add_filter( 'rwmb_meta_boxes', array($this, 'author_twitter'));
		add_filter( 'rwmb_meta_boxes', array($this, 'reviews'));
	}

	public function authors_news( $meta_boxes ) {
		$meta_boxes[] = [
			"title" => __("Related Authors", 'cranleigh-2016'),
			"post_types" => ["post"],
			"priority" => 'high',
			'context' => 'side',
			'fields' => [
				[
					'id' => 'related_authors',
					'name' => 'Related Authors',
					'type' => 'post',
					'post_type' => 'book_author',
					'clone' => true
				]
			]
		];

		$meta_boxes[] = [
			"title" => __("Related Books", 'cranleigh-2016'),
			"post_types" => ["post", "review"],
			"priority" => 'high',
			'context' => 'side',
			'fields' => [
				[
					'id' => 'related_books',
					'name' => 'Related Books',
					'type' => 'post',
					'post_type' => 'book',
					'clone' => true
				]
			]
		];

		return $meta_boxes;
	}

	public function author_twitter( $meta_boxes ) {
		$meta_boxes[] = [
			"title" => __( "Twitter Handle", "cranleigh-2016"),
			"post_types" => Author::getPostTypeKey(),
			"priority" => "high",
			"context" => "side",
			"fields" => [
				[
					"id" => "author_twitter_handle",
					"name" => "Author Twitter Handle",
					"type" => "text"
				]
			]
		];
		return $meta_boxes;
	}

	public function reviews( $meta_boxes ) {
		$meta_boxes[] = [
			"title" => __( "Review Meta", "cranleigh-2016"),
			"post_types" => Review::getPostTypeKey(),
			"context" => "side",
			"priority" => "high",
			"fields" => [
				[
					"id" => "review_author",
					"name" => "Reviewer Name",
					"type" => "text"
				],
				[
					"id" => "review_author_age",
					"name" => "Reviewer Age",
					"type" => "number"
				],
				[
					"id" => "review_score",
					"name" => "Score",
					"type" => "slider",
					"suffix" => "/5",
					"js_options" => [
						"min" => 0,
						"max" => 5,
						"step" => 1
					],
					"std" => 0
				]
			]
		];
		return $meta_boxes;
	}

	public function sponsors( $meta_boxes ) {
		$meta_boxes[] = [
			"title" => __( "Meta", 'cranleigh-2016'),
			"post_types" => Partner::getPostTypeKey(),
			'priority' => 'high',
			'context' => 'side',
			'fields' => [
				[
					'id' => 'sponsor_url',
					'name' => "Sponsor's Website",
					'type' => 'url'
				]
			]
		];

		return $meta_boxes;
	}

	public function book( $meta_boxes ) {
		$meta_boxes[] = array(
			'title'      => __( 'Meta', 'cranleigh-2016' ),
			'post_types' => 'book',
			'priority' => 'high',
			'context' => 'side',
			'fields'     => array(

				array(
					'id'   => 'author',
					'name' => __( 'Book Author', 'cranleigh-2016' ),
					'type' => 'post',
					'post_type' => 'book_author'
				),

				array(
					'id' => 'peagreenlink',
					'name' => __( 'Pea Green Boat Link', 'cranleigh-2016'),
					'type' => 'url',
					'desc' => 'The URL to the `product page` on Pea Green Boat Books'
				),
				
			),
		);
		return $meta_boxes;
	}

	public function book_scoring( $meta_boxes ) {
		$meta_boxes[] = array(
			'title' => __('Scoring', 'cranleigh-2016'),
			'post_types' => 'book',
			'context'=> 'side',
			'priority' => 'low',
			'fields' => array(
				array(
					'id' => 'score',
					'name' => __( 'School\'s Book Score', 'cranleigh-2016'),
					'type' => 'number',
					'desc' => 'Once voting has been completed, pop in the number of votes that this book received.'
				)
			)
		);
		return $meta_boxes;
	}

}