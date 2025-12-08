<?php
/**
 * Registers the Game taxonomy for Casinos.
 *
 * @package Filtering
 */

namespace Filtering\Storage;

class GameTaxonomy {

	/**
	 * @var string
	 */
	private $taxonomy = 'filtering_game';

	/**
	 * @var string
	 */
	private $object_type = 'filtering_casino';

	/**
	 * @var string[]
	 */
	private $terms = array(
		'Blackjack',
		'Slots',
		'Live Dealer',
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register taxonomy.
	 *
	 * @return void
	 */
	public function register() {
		$labels = array(
			'name'          => __( 'Games', 'filtering' ),
			'singular_name' => __( 'Game', 'filtering' ),
			'menu_name'     => __( 'Games', 'filtering' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => false,
			'query_var'         => false,
			'rewrite'           => false,
		);

		register_taxonomy( $this->taxonomy, array( $this->object_type ), $args );

		$this->register_terms();
	}

	/**
	 * Insert default terms if missing.
	 *
	 * @return void
	 */
	private function register_terms() {
		foreach ( $this->terms as $term ) {
			if ( ! term_exists( $term, $this->taxonomy ) ) {
				wp_insert_term( $term, $this->taxonomy );
			}
		}
	}

}
