<?php
/**
 * Understrap Theme Customizer
 *
 * @package understrap
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if ( ! function_exists( 'understrap_customize_register' ) ) {
	/**
	 * Register basic customizer support.
	 *
	 * @param object $wp_customize Customizer reference.
	 */
	function understrap_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	}
}
add_action( 'customize_register', 'understrap_customize_register' );

if ( ! function_exists( 'understrap_theme_customize_register' ) ) {
	/**
	 * Register individual settings through customizer's API.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function understrap_theme_customize_register( $wp_customize ) {
		$wp_customize->add_section('awesome_book_awards_settings', array(
			'title' => 'Awesome Books Awards',
			'capability' => 'edit_theme_options',
			'description' => 'Edit Theme Options',
			'priority' => 1
		));
		$wp_customize->add_setting('show_cta_button', array(
			'default' => 0,
			'type' => 'theme_mod'
		));
		$wp_customize->add_setting('url_cta_button', array(
			'type' => 'theme_mod',
			'default' => null
		));
		$wp_customize->add_setting('label_cta_button', array(
			'type' => 'theme_mod',
			'default' => null
		));
		$wp_customize->add_setting('awesome_year', array(
			'type' => 'theme_mod',
			'defult' => date('Y')
		));
		$wp_customize->add_setting('voting_boolean', array(
			'type' => 'theme_mod',
			'default' => 0
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			'awesome_year',
			array(
				'label' => __('Awesome Year', "cranleigh-2016"),
				'section' => "awesome_book_awards_settings",
				'type' => 'number',
			)
		));
		$wp_customize->add_control(new WP_Customize_Control(
                       	$wp_customize,
                       	'voting_boolean',
                       	array(
                               	'label' => __("Voting On/Off", 'cranleigh-2016'),
                               	"section" => "awesome_book_awards_settings",
                               	"settings" => "voting_boolean",
                               	"type" => "radio",
                               	"choices" => array(
                                       	true => "On",
                                       	false => "Off"
                               	)
                       	)
                ));

		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			'show_cta_button',
			array(
				'label' => __("Show CTA Button", 'cranleigh-2016'),
				"section" => "awesome_book_awards_settings",
				"settings" => "show_cta_button",
				"type" => "radio",
				"choices" => array(
					true => "Yes",
					false => "No"
				)
			)
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			'label_cta_button',
			array(
				'label' => __("Label for CTA Button", 'cranleigh-2016'),
				'section' => "awesome_book_awards_settings",
				"settings" => "label_cta_button",
				"type" => "text"
			)
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			'url_cta_button',
			array(
				'label' => __("URL for CTA Button", 'cranleigh-2016'),
				'section' => "awesome_book_awards_settings",
				"settings" => "url_cta_button",
				"type" => "url"
			)
		));

		// Theme layout settings.
		$wp_customize->add_section( 'understrap_theme_layout_options', array(
			'title'       => __( 'Theme Layout Settings', 'understrap' ),
			'capability'  => 'edit_theme_options',
			'description' => __( 'Container width and sidebar defaults', 'understrap' ),
			'priority'    => 160,
		) );

		$wp_customize->add_setting( 'understrap_container_type', array(
			'default'           => 'container',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_textarea',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'container_type', array(
					'label'       => __( 'Container Width', 'understrap' ),
					'description' => __( "Choose between Bootstrap's container and container-fluid", 'understrap' ),
					'section'     => 'understrap_theme_layout_options',
					'settings'    => 'understrap_container_type',
					'type'        => 'select',
					'choices'     => array(
						'container'       => __( 'Fixed width container', 'understrap' ),
						'container-fluid' => __( 'Full width container', 'understrap' ),
					),
					'priority'    => '10',
				)
			) );

		$wp_customize->add_setting( 'understrap_sidebar_position', array(
			'default'           => 'right',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_textarea',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'understrap_sidebar_position', array(
					'label'       => __( 'Sidebar Positioning', 'understrap' ),
					'description' => __( "Set sidebar's default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.",
					'understrap' ),
					'section'     => 'understrap_theme_layout_options',
					'settings'    => 'understrap_sidebar_position',
					'type'        => 'select',
					'choices'     => array(
						'right' => __( 'Right sidebar', 'understrap' ),
						'left'  => __( 'Left sidebar', 'understrap' ),
						'both'  => __( 'Left & Right sidebars', 'understrap' ),
						'none'  => __( 'No sidebar', 'understrap' ),
					),
					'priority'    => '20',
				)
			) );
	}
} // endif function_exists( 'understrap_theme_customize_register' ).
add_action( 'customize_register', 'understrap_theme_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
if ( ! function_exists( 'understrap_customize_preview_js' ) ) {
	/**
	 * Setup JS integration for live previewing.
	 */
	function understrap_customize_preview_js() {
		wp_enqueue_script( 'understrap_customizer', get_template_directory_uri() . '/js/customizer.js',
			array( 'customize-preview' ), '20130508', true );
	}
}
add_action( 'customize_preview_init', 'understrap_customize_preview_js' );
