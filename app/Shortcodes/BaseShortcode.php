<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 16:15
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\Shortcodes;


/**
 * Class BaseShortcode
 *
 * @package CranleighSchool\AwesomeBookAwardsTheme\Shortcodes
 */
abstract class BaseShortcode {

	/**
	 * BaseShortcode constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 *
	 */
	private function init() {
		add_shortcode($this->tag, array($this, 'render'));
	}

	/**
	 * @param string|array       $atts
	 * @param string|null $content
	 *
	 * @return mixed
	 */
	abstract public function render($atts, string $content = null);
}
