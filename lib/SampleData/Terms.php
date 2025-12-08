<?php
/**
 * Seed default terms for Filtering taxonomies.
 *
 * Assumes taxonomies already exist.
 *
 * @package Filtering
 */

namespace Filtering\SampleData;

class Terms {

	/**
	 * Map of taxonomy => term names.
	 *
	 * @var array<string, string[]>
	 */
	private $terms_map = array(
		'filtering_type'    => array(
			'Social',
			'No Deposit',
			'Sweepstakes',
			'Fast Paying',
			'Online Casino',
		),
		'filtering_game'    => array(
			'Blackjack',
			'Slots',
			'Live Dealer',
		),
		'filtering_banking' => array(
			'Credit Card',
			'Venmo',
			'PayPal',
		),
		'filtering_payouts' => array(
			'Up To 1 Week',
			'1-2 Days',
			'24 Hours',
		),
	);

	/**
	 * Insert terms into existing taxonomies.
	 *
	 * @return void
	 */
	public function insert_terms() {
		foreach ( $this->terms_map as $taxonomy => $terms ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			foreach ( $terms as $term ) {
				if ( term_exists( $term, $taxonomy ) ) {
					continue;
				}

				wp_insert_term( $term, $taxonomy );
			}
		}
	}

}
