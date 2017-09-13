<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 13/09/2017
 * Time: 11:19
 */

namespace CranleighSchool\AwesomeBookAwardsTheme;


class ThemeUpdateChecker {

	private $stylesheet_directory;

	public function __construct() {

		$update = \Puc_v4_Factory::buildUpdateChecker(
			$this->ThemeURI,
			$this->functionsFilePath(),
			$this->themeFolderName()
		);

		$update->setBranch('master');
		$this->updateobj = $update;

	}

	private function functionsFilePath() {
		//return dirname(dirname(__FILE__));
		$this->stylesheet_directory = get_stylesheet_directory();

		return $this->stylesheet_directory.'/functions.php';
	}

	private function themeFolderName() {
		$parts = explode("/", $this->stylesheet_directory);
		return end($parts);
	}

	public function __get( $name ) {

		return wp_get_theme()->get($name);
	}
}