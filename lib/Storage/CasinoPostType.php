<?php

namespace Filtering\Storage;

class CasinoPostType {

    /**
	 * Post type slug.
	 *
	 * @var string
	 */
	private $post_type = 'filtering_casino';

    /**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register() {
		$labels = array(
			'name'               => __( 'Casinos', 'filtering' ),
			'singular_name'      => __( 'Casino', 'filtering' ),
			'menu_name'          => __( 'Casinos', 'filtering' ),
			'name_admin_bar'     => __( 'Casino', 'filtering' ),
			'add_new'            => __( 'Add New', 'filtering' ),
			'add_new_item'       => __( 'Add New Casino', 'filtering' ),
			'new_item'           => __( 'New Casino', 'filtering' ),
			'edit_item'          => __( 'Edit Casino', 'filtering' ),
			'view_item'          => __( 'View Casino', 'filtering' ),
			'all_items'          => __( 'All Casinos', 'filtering' ),
			'search_items'       => __( 'Search Casinos', 'filtering' ),
			'not_found'          => __( 'No casinos found.', 'filtering' ),
			'not_found_in_trash' => __( 'No casinos found in Trash.', 'filtering' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor' ),
			'rewrite'             => false,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-tickets-alt',
		);

		register_post_type( $this->post_type, $args );
	}

}
