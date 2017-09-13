<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 12/09/2017
 * Time: 11:42
 */

namespace CranleighSchool\AwesomeBookAwardsTheme;


class MetaBoxes {
	public function __construct() {
		add_filter( 'rwmb_meta_boxes', array($this,'book') );
		add_filter( 'rwmb_meta_boxes', array($this, 'book_scoring') );

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