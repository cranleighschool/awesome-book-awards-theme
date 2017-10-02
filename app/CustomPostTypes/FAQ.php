<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 09:29
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes;


class FAQ extends BaseType {

	protected static $post_type_key = "faq";

	public function setup() {

		$this->setIcon('dashicons-carrot');
		$this->setOptions(["supports"=>["page-attributes", "title", "editor"], "has_archive" => false]);
		$this->setLabels(['new_item' => 'FAQ', 'add_new_item' => 'Add a New Frequently Asked Question', 'name' => 'FAQs', 'menu_name' => 'FAQs', 'all_items' => 'FAQs']);
	}

	public function render() {

	}

}
