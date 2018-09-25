<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 09:29
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;


class School extends BaseType {

	protected static $post_type_key = "school";

	public function setup() {
		$this->setIcon('dashicons-building');
		$this->setOptions(['supports'=>['title', 'thumbnail'], "has_archive" => false]);
		$this->setLabels(['featured_image' => "School Logo"]);
	}

	public function isDivisibleBy15(int $var) {
	    if ($var % 15 == 0) {
	        return true;
        } else {
	        return false;
        }
    }
	public function render() {
		$this->post_type->taxonomy('awesome-year');
        $this->post_type->columns()->hide('date');
	$this->post_type->columns()->add([
            'school_contact' => __('School Contact'),
]);
		if (get_theme_mod('voting_boolean') == true) {
        $this->post_type->columns()->add([
            'school_contact' => __('School Contact'),
            'fav_book' => __('This Year\'s Favourite Book'),
            'numkids' => __('# Kids This Year')
        ]);
	}

        $this->post_type->columns()->populate('school_contact', function($column, $post_id) {
            $email = get_post_meta($post_id, 'school_contact', true);
            $contact = get_post_meta($post_id, 'school_contactname', true);
            echo '<a href="mailto:'.$email.'">'.$contact.'<a/>';
        });

        $this->post_type->columns()->populate('numkids', function($columns, $post_id) {
           $all_books = $this->thisYearsBooks();
           $return = 0;

           foreach($all_books as $book):
               if (!get_post_meta($post_id, 'book_id_'.$book, true)) {
                   $return = "Incomplete Data";
                   break;
               }
               $return = $return + get_post_meta($post_id, 'book_id_'.$book, true);
           endforeach;

           if (is_string($return)) {
               echo $return;
           } else {
               if ($this->isDivisibleBy15($return))
                   echo $return / 15;
               else
                   echo '<strong style="color:red">Error - kid to score calculation wrong</strong>';
           }

        });

        $this->post_type->columns()->populate('fav_book', function($column, $post_id) {
            $all_books = $this->thisYearsBooks();
            $scores = array();
            foreach($all_books as $book):

                if (!get_post_meta($post_id, 'book_id_'.$book, true)) {
                    $return = "Incomplete Data";
                    break;
                }

                $scores[$book] = get_post_meta($post_id, 'book_id_'.$book, true);

            endforeach;

            arsort($scores);
            if (isset($return)) {
                echo $return;
            } else {
                $fav_book = array_keys($scores)[0];
                $fav_book = get_post($fav_book);

                $image = get_the_post_thumbnail($fav_book->ID, 'thumb');
                echo '<a href="'.get_permalink($fav_book->ID).'">'.$image.'</a>';
            }
        });
	}

	public function thisYearsBooks() {
        $args = [
            "posts_per_page" => -1,
            "post_type" => "book",
            "tax_query" => array(
                array(
                    'taxonomy' => 'awesome-year',
                    'field' => 'slug',
                    'terms' => get_theme_mod('awesome_year'),
                )
            )
        ];

        $books = new \WP_Query($args);
        $book_ids = array();

        error_log(print_r($books->posts, true));

        while($books->have_posts()): $books->the_post();
            $book_ids[] = get_the_ID();
        endwhile;
        return $book_ids;
    }


}
