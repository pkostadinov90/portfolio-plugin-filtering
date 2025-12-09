<?php
/**
 * Assets loader.
 *
 * Enqueues frontend CSS and JS for the Filtering plugin.
 *
 * @package Filtering
 */

namespace Filtering;

class Assets {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
	}

	/**
	 * Get asset version based on filemtime.
	 *
	 * No explicit file existence checks; if the file is missing,
	 * filemtime will return false and WordPress will treat version as empty.
	 *
	 * @param string $relative_path Path relative to plugin root.
	 * @return int|false
	 */
	private function get_version( $path ) {
		// Silence warnings if the file doesn't exist.
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return @filemtime( $path );
	}

	/**
	 * Enqueue frontend assets.
	 *
	 * @return void
	 */
	public function enqueue_frontend() {
		$css_rel_path = '/assets/dist/styles/frontend.css';
		wp_enqueue_style(
			'filtering-frontend',
			FILTERING_URL . $css_rel_path,
			array(),
			$this->get_version( FILTERING_DIR . $css_rel_path )
		);

		$js_rel_path  = '/assets/dist/scripts/frontend.js';
		wp_enqueue_script(
			'filtering-frontend',
			FILTERING_URL . $js_rel_path,
			array(),
			$this->get_version( FILTERING_DIR . $js_rel_path ),
			true
		);

		wp_localize_script(
			'filtering-frontend',
			'FilteringRT',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'filtering_get_result' ),
			)
		);
	}

}
