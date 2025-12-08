<?php
/**
 * Registers the Type taxonomy for Casinos.
 *
 * @package Filtering
 */

namespace Filtering\Storage;

class TypeTaxonomy {

	/**
	 * Taxonomy slug.
	 *
	 * @var string
	 */
	private $taxonomy = 'filtering_type';

	/**
	 * Object type (CPT) slug.
	 *
	 * @var string
	 */
	private $object_type = 'filtering_casino';

	/**
	 * Default terms.
	 *
	 * @var string[]
	 */
	private $terms = array(
		'Social',
		'No Deposit',
		'Sweepstakes',
		'Fast Paying',
		'Online Casino',
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
			'name'          => __( 'Types', 'filtering' ),
			'singular_name' => __( 'Type', 'filtering' ),
			'menu_name'     => __( 'Types', 'filtering' ),
			'search_items'  => __( 'Search Types', 'filtering' ),
			'all_items'     => __( 'All Types', 'filtering' ),
			'edit_item'     => __( 'Edit Type', 'filtering' ),
			'update_item'   => __( 'Update Type', 'filtering' ),
			'add_new_item'  => __( 'Add New Type', 'filtering' ),
			'new_item_name' => __( 'New Type Name', 'filtering' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'hierarchical'       => false,
			'query_var'          => false,
			'rewrite'            => false,
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
