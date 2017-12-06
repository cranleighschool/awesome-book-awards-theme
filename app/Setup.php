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
use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\FAQ;
use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\School;
use CranleighSchool\AwesomeBookAwardsTheme\CustomPostTypes\Partner;
use CranleighSchool\AwesomeBookAwardsTheme\Widgets\CranleighRecentPosts;
use CranleighSchool\AwesomeBookAwardsTheme\Widgets\RelatedPosts;

class Setup {

	static public function run() {
		add_image_size( 'book-cover', 734, 1124, true );
		add_image_size( 'author-mugshot', 200, 300, true);
		add_image_size( 'landscape', 325, 225, true);
		add_action('after_setup_theme', array(self::class, 'understrap_setup'));
		add_action('wp_enqueue_scripts', array(self::class, 'enqueue_scripts'));
		add_action('admin_enqueue_scripts', array(self::class, 'enqueue_scripts'));
		add_action('wp_head', array(self::class, 'googleanalytics'));
		add_action('init', array(self::class, 'ryanbenhase_unregister_taxes'));
		add_filter('oembed_dataparse', array(self::class,'oembed_video_add_wrapper'),10,3);
		//add_editor_style("css/editor-style.css");

		$fix_attachementPages = new HideAttachmentPages();

	}

	static public function currentUrl($add_trailing_slash=false) {
		global $wp;
		$url = home_url(add_query_arg(array(),$wp->request));

		if ($add_trailing_slash==true) {
			return $url."/";
		} else {
			return $url;
		}
	}

	static public function Widgets() {
		add_action('widgets_init', function() {
			register_widget(CranleighRecentPosts::class);
			register_widget(RelatedPosts::class);
		});
	}

	static public function oembed_video_add_wrapper($return, $data, $url) {
		return '<div class="oembed_wrap '.$data->provider_name.'">'.$return.'</div>';
	}

	static public function modify_read_more_link() {
		$style = "signpost";
		return '<a class="'.$style.'" href="'.get_permalink().'">Read More <i class="fa fa-fw fa-chevron-right"></i></a>';
	}

	static public function CustomPostTypes() {
		$book = new Book();
		$author = new Author();
		$school = new School();
		$faq = new FAQ();
		$partner = new Partner();

		$book->init();
		$author->init();
		$school->init();
		$faq->init();
		$partner->init();
	}

	static public function ryanbenhase_unregister_taxes() {
		unregister_taxonomy_for_object_type( 'post_tag', 'post' );
		unregister_taxonomy_for_object_type( 'category', 'post' );
	}

	static public function ThemeUpdateChecker() {
		new ThemeUpdateChecker();
	}

	static public function Shortcodes() {
		new Shortcodes\BookList();
		new Shortcodes\FAQs();
	}

	static public function MetaBoxes() {
		new MetaBoxes();
	}

	public static function enqueue_scripts() {
		wp_enqueue_style('aspect-font', '//cdn.cranleigh.org/css/AspW-Rg.css');
		wp_enqueue_style('bentonsans', '//cdn.cranleigh.org/fonts/bentonsans/fontface.css');

	}

	public static function googleanalytics() {
		if ($_SERVER['HTTP_HOST']=='frbdev.cranleigh.org' || is_user_logged_in()) {
			echo '<!-- Analytics Aborted because you\'re either logged in, or you are on HTTP HOST -->';
			return false;
		}
		?>
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="//www.googletagmanager.com/gtag/js?id=UA-42791789-4"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments)};
	gtag('js', new Date());

	gtag('config', 'UA-42791789-4');

</script>
<?php
	}
	public static function understrap_setup() {
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
