<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://hashcodeab.se
 * @since      1.0.0
 *
 * @package    Content_Topics_Hashcode
 * @subpackage Content_Topics_Hashcode/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Content_Topics_Hashcode
 * @subpackage Content_Topics_Hashcode/includes
 * @author     Dhanuka Gunarathna <dhanuka@hashcodeab.se>
 */
class Content_Topics_Hashcode_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'content-topics-hashcode',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
