<?php
/**
 * Main plugin class (Singleton).
 *
 * @package Filtering
 */

namespace Filtering;

class Plugin {

	/**
	 * The single instance of the class.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the single instance of the class.
	 *
	 * @return Plugin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 *
	 * Keep this private to enforce singleton.
	 */
	private function __construct() {
		// Intentionally left blank.
	}

	/**
	 * Prevent cloning.
	 *
	 * @return void
	 */
	private function __clone() {
		// Intentionally left blank.
	}

	/**
	 * Prevent unserializing.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		die();
	}

	/**
	 * Runs on plugin activation.
	 *
	 * @return void
	 */
	public static function activate() {
		// Activation logic.
	}

	/**
	 * Runs on plugin deactivation.
	 *
	 * @return void
	 */
	public static function deactivate() {
		// Deactivation logic.
	}

	/**
	 * Initialize plugin functionality.
	 *
	 * @return void
	 */
	public function init() {
		new Storage/CasinoPostType();

		new Storage/TypeTaxonomy();
		new Storage/GameTaxonomy();
		new Storage/BankingTaxonomy();
		new Storage/PayoutsTaxonomy();
	}

}
