<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 18/08/2017
 * Time: 10:20
 */

namespace CranleighSchool\AwesomeBookAwardsTheme;


use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Author;
use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Book;
use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\School;

class Setup {


	static public function run() {
		add_action('after_setup_theme', array(self::class, 'understrap_setup'));
		add_action('wp_enqueue_scripts', array(self::class, 'enqueue_scripts'));
	}
	static public function CustomPostTypes() {
		(new Book())->init();
		(new Author())->init();
		(new School())->init();
	}
	static public function ThemeUpdateChecker() {
		new ThemeUpdateChecker();
	}
	static public function MetaBoxes() {
		new MetaBoxes();
	}
	public function enqueue_scripts() {
		wp_enqueue_style('aspect-font', '//cdn.cranleigh.org/css/AspW-Rg.css');
		wp_enqueue_style('bentonsans', '//cdn.cranleigh.org/fonts/bentonsans/fontface.css');
	}

	public function understrap_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on understrap, use a find and replace
		 * to change 'understrap' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'awesome-book-awards', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'understrap' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Adding Thumbnail basic support
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Adding support for Widget edit icons in customizer
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'understrap_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Set up the Wordpress Theme logo feature.
		add_theme_support( 'custom-logo' );

		// Check and setup theme default settings.
		setup_theme_default_settings();
	}

}