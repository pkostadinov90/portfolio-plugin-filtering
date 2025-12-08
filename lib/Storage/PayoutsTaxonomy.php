<?php
/**
 * Registers the Payouts taxonomy for Casinos.
 *
 * @package Filtering
 */

namespace Filtering\Storage;

class PayoutsTaxonomy {

	/**
	 * @var string
	 */
	private $taxonomy = 'filtering_payouts';

	/**
	 * @var string
	 */
	private $object_type = 'filtering_casino';

	/**
	 * @var string[]
	 */
	private $terms = array(
		'Up To 1 Week',
		'1-2 Days',
		'24 Hours',
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
			'name'          => __( 'Payout Speeds', 'filtering' ),
			'singular_name' => __( 'Payout Speed', 'filtering' ),
			'menu_name'     => __( 'Payouts', 'filtering' ),
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
