<?php
/**
 * ACF Local JSON overrides.
 *
 * Stores and loads ACF JSON from this plugin instead of the theme.
 *
 * @package Filtering
 */

namespace Filtering\ACF;

class Overrides {

	/**
	 * Plugin ACF JSON directory.
	 *
	 * @var string
	 */
	private $json_dir;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->json_dir = FILTERING_DIR . '/acf-json';

		// add_filter( 'acf/settings/save_json', array( $this, 'save_json_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'load_json_paths' ) );
	}

	/**
	 * Override where ACF saves JSON.
	 *
	 * @param string $path Default path.
	 * @return string
	 */
	public function save_json_path( $path ) {
		return $this->json_dir;
	}

	/**
	 * Override where ACF loads JSON from.
	 *
	 * @param array<int, string> $paths Default paths.
	 * @return array<int, string>
	 */
	public function load_json_paths( $paths ) {
		$paths = (array) $paths;

		// Ensure plugin path is included.
		$paths[] = $this->json_dir;

		return $paths;
	}

}
