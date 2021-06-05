<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Share_The_Word
 * @subpackage Share_The_Word/includes
 * @author     Tobias Krause <tobias_krause@akranet.de>
 */
class Share_The_Word_i18n {

	private $domain;

	public function __construct( $domain = 'share-the-word' ) {
		$this->domain = $domain;
	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
