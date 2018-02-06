<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 11/12/2017
 * Time: 08:51
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;


class Review extends BaseType {
	protected static $post_type_key = "review";

	public function setup() {
		$this->setIcon('dashicons-building');

		$this->setOptions([
		    'supports'=>[
		        'title',
                'thumbnail',
                'editor'
            ],
            "has_archive" => true,
            "rewrite"  => [
                "slug" => "reviews"
            ]
        ]);

		$this->setLabels(['featured_image' => "Book Cover"]);


    }
    public function get_the_archive_title($title) {
        if( is_post_type_archive( $this->post_type_key ) ) {

            $title = "Reader Reviews";

        }

        return $title;
    }

	public function render() {
		$this->post_type->columns()->add([
			'book' => __('Book')
		]);

		$this->post_type->columns()->populate('book', function($column, $post_id) {
			$array = [];
			foreach (get_post_meta($post_id, 'related_books', true) as $book):
				$array[] = get_post($book)->post_title;
			endforeach;
			echo implode(", ", $array);
		});

        add_filter("get_the_archive_title", array($this, "get_the_archive_title"));

    }
}
