<?php
/**
 * Registers the Banking taxonomy for Casinos.
 *
 * @package Filtering
 */

namespace Filtering\Storage;

class BankingTaxonomy {

	/**
	 * @var string
	 */
	private $taxonomy = 'filtering_banking';

	/**
	 * @var string
	 */
	private $object_type = 'filtering_casino';

	/**
	 * @var string[]
	 */
	private $terms = array(
		'Credit Card',
		'Venmo',
		'PayPal',
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
			'name'          => __( 'Banking Methods', 'filtering' ),
			'singular_name' => __( 'Banking Method', 'filtering' ),
			'menu_name'     => __( 'Banking', 'filtering' ),
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
