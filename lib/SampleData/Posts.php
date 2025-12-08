<?php
/**
 * Seed sample Casino posts from a hardcoded dataset.
 *
 * @package Filtering
 */

namespace Filtering\SampleData;

class Posts {

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	private $post_type = 'filtering_casino';

	/**
	 * Hardcoded sample casinos.
	 *
	 * Each item:
	 * - title: short fake name
	 * - content: 2-5 short features (<= 45 chars)
	 * - terms: one term per taxonomy
	 * - meta: all underscore-prefixed meta keys
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private function get_casinos() {
		return array(
			array(
				'title'   => 'Vanta',
				'content' => 'Fast cashout, VIP perks',
				'terms'   => array(
					'filtering_type'    => 'Fast Paying',
					'filtering_game'    => 'Slots',
					'filtering_banking' => 'PayPal',
					'filtering_payouts' => '24 Hours',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Limited-time bonus this week',
					'_filtering_casino_review_link'  => 'https://example.com/review/vanta',
					'_filtering_casino_review_title' => 'Vanta full review',
					'_filtering_casino_promo_code'   => 'VAN123',
					'_filtering_casino_slot_count'   => '840',
					'_filtering_casino_packages'     => '1',
					'_filtering_casino_vip'          => '1',
					'_filtering_casino_link'         => 'https://example.com/casino/vanta',
				),
			),
			array(
				'title'   => 'Nova',
				'content' => 'Daily spins, Mobile first',
				'terms'   => array(
					'filtering_type'    => 'Social',
					'filtering_game'    => 'Live Dealer',
					'filtering_banking' => 'Credit Card',
					'filtering_payouts' => '1-2 Days',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Fresh rewards for new players',
					'_filtering_casino_review_link'  => 'https://example.com/review/nova',
					'_filtering_casino_review_title' => 'Nova expert overview',
					'_filtering_casino_promo_code'   => 'NOV123',
					'_filtering_casino_slot_count'   => '420',
					'_filtering_casino_packages'     => '0',
					'_filtering_casino_vip'          => '1',
					'_filtering_casino_link'         => 'https://example.com/casino/nova',
				),
			),
			array(
				'title'   => 'Lumo',
				'content' => 'Low wager, Instant play',
				'terms'   => array(
					'filtering_type'    => 'No Deposit',
					'filtering_game'    => 'Slots',
					'filtering_banking' => 'Venmo',
					'filtering_payouts' => '1-2 Days',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'New player offer, quick sign-up',
					'_filtering_casino_review_link'  => 'https://example.com/review/lumo',
					'_filtering_casino_review_title' => 'Lumo bonus and payouts',
					'_filtering_casino_promo_code'   => 'LUM123',
					'_filtering_casino_slot_count'   => '1200',
					'_filtering_casino_packages'     => '1',
					'_filtering_casino_vip'          => '0',
					'_filtering_casino_link'         => 'https://example.com/casino/lumo',
				),
			),
			array(
				'title'   => 'Rift',
				'content' => 'Quick support, High RTP',
				'terms'   => array(
					'filtering_type'    => 'Online Casino',
					'filtering_game'    => 'Blackjack',
					'filtering_banking' => 'Credit Card',
					'filtering_payouts' => 'Up To 1 Week',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Weekend special',
					'_filtering_casino_review_link'  => 'https://example.com/review/rift',
					'_filtering_casino_review_title' => 'Rift games and banking',
					'_filtering_casino_promo_code'   => 'RIF123',
					'_filtering_casino_slot_count'   => '300',
					'_filtering_casino_packages'     => '0',
					'_filtering_casino_vip'          => '0',
					'_filtering_casino_link'         => 'https://example.com/casino/rift',
				),
			),
			array(
				'title'   => 'Ember',
				'content' => 'Fast cashout, Live tables',
				'terms'   => array(
					'filtering_type'    => 'Sweepstakes',
					'filtering_game'    => 'Live Dealer',
					'filtering_banking' => 'PayPal',
					'filtering_payouts' => '24 Hours',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Exclusive perks with quick payouts',
					'_filtering_casino_review_link'  => 'https://example.com/review/ember',
					'_filtering_casino_review_title' => 'Ember full review',
					'_filtering_casino_promo_code'   => 'EMB123',
					'_filtering_casino_slot_count'   => '980',
					'_filtering_casino_packages'     => '1',
					'_filtering_casino_vip'          => '1',
					'_filtering_casino_link'         => 'https://example.com/casino/ember',
				),
			),

			// --- Add more following the same pattern (copy/paste friendly). ---

			array(
				'title'   => 'Pulse',
				'content' => 'Mobile first, Daily spins',
				'terms'   => array(
					'filtering_type'    => 'Social',
					'filtering_game'    => 'Slots',
					'filtering_banking' => 'Venmo',
					'filtering_payouts' => '1-2 Days',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Daily surprises',
					'_filtering_casino_review_link'  => 'https://example.com/review/pulse',
					'_filtering_casino_review_title' => 'Pulse expert overview',
					'_filtering_casino_promo_code'   => 'PUL123',
					'_filtering_casino_slot_count'   => '650',
					'_filtering_casino_packages'     => '0',
					'_filtering_casino_vip'          => '1',
					'_filtering_casino_link'         => 'https://example.com/casino/pulse',
				),
			),
			array(
				'title'   => 'Glyph',
				'content' => 'Safe banking, Instant play',
				'terms'   => array(
					'filtering_type'    => 'Online Casino',
					'filtering_game'    => 'Blackjack',
					'filtering_banking' => 'Credit Card',
					'filtering_payouts' => 'Up To 1 Week',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Safe banking focus',
					'_filtering_casino_review_link'  => 'https://example.com/review/glyph',
					'_filtering_casino_review_title' => 'Glyph full review',
					'_filtering_casino_promo_code'   => 'GLY123',
					'_filtering_casino_slot_count'   => '210',
					'_filtering_casino_packages'     => '0',
					'_filtering_casino_vip'          => '0',
					'_filtering_casino_link'         => 'https://example.com/casino/glyph',
				),
			),
			array(
				'title'   => 'Axiom',
				'content' => 'Weekend drops, High RTP',
				'terms'   => array(
					'filtering_type'    => 'Fast Paying',
					'filtering_game'    => 'Slots',
					'filtering_banking' => 'PayPal',
					'filtering_payouts' => '24 Hours',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Weekend special',
					'_filtering_casino_review_link'  => 'https://example.com/review/axiom',
					'_filtering_casino_review_title' => 'Axiom bonus and payouts',
					'_filtering_casino_promo_code'   => 'AXI123',
					'_filtering_casino_slot_count'   => '1440',
					'_filtering_casino_packages'     => '1',
					'_filtering_casino_vip'          => '1',
					'_filtering_casino_link'         => 'https://example.com/casino/axiom',
				),
			),
			array(
				'title'   => 'Wisp',
				'content' => 'Low wager, Quick support',
				'terms'   => array(
					'filtering_type'    => 'No Deposit',
					'filtering_game'    => 'Slots',
					'filtering_banking' => 'Venmo',
					'filtering_payouts' => '1-2 Days',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Limited spots this week',
					'_filtering_casino_review_link'  => 'https://example.com/review/wisp',
					'_filtering_casino_review_title' => 'Wisp games and banking',
					'_filtering_casino_promo_code'   => 'WIS123',
					'_filtering_casino_slot_count'   => '500',
					'_filtering_casino_packages'     => '0',
					'_filtering_casino_vip'          => '0',
					'_filtering_casino_link'         => 'https://example.com/casino/wisp',
				),
			),
			array(
				'title'   => 'Comet',
				'content' => 'Fast cashout, New releases',
				'terms'   => array(
					'filtering_type'    => 'Sweepstakes',
					'filtering_game'    => 'Live Dealer',
					'filtering_banking' => 'PayPal',
					'filtering_payouts' => '24 Hours',
				),
				'meta'    => array(
					'_filtering_casino_callout'      => 'Fresh rewards',
					'_filtering_casino_review_link'  => 'https://example.com/review/comet',
					'_filtering_casino_review_title' => 'Comet full review',
					'_filtering_casino_promo_code'   => 'COM123',
					'_filtering_casino_slot_count'   => '1100',
					'_filtering_casino_packages'     => '1',
					'_filtering_casino_vip'          => '1',
					'_filtering_casino_link'         => 'https://example.com/casino/comet',
				),
			),

			// --- Duplicate these blocks until you reach 156. ---
		);
	}

	/**
	 * Insert posts by looping the dataset.
	 *
	 * @return int Number of successfully created posts.
	 */
	public function insert_posts() {
		$casinos = $this->get_casinos();
		$created = 0;

		foreach ( $casinos as $casino ) {
			$post_id = wp_insert_post(
				array(
					'post_type'    => $this->post_type,
					'post_status'  => 'publish',
					'post_title'   => $casino['title'],
					'post_content' => $casino['content'],
				),
				true
			);

			if ( is_wp_error( $post_id ) || empty( $post_id ) ) {
				continue;
			}

			// Assign one term per taxonomy by name.
			if ( ! empty( $casino['terms'] ) && is_array( $casino['terms'] ) ) {
				foreach ( $casino['terms'] as $taxonomy => $term_name ) {
					if ( taxonomy_exists( $taxonomy ) && ! empty( $term_name ) ) {
						wp_set_object_terms( $post_id, $term_name, $taxonomy, false );
					}
				}
			}

			// Assign meta.
			if ( ! empty( $casino['meta'] ) && is_array( $casino['meta'] ) ) {
				foreach ( $casino['meta'] as $meta_key => $meta_value ) {
					update_post_meta( $post_id, $meta_key, $meta_value );
				}
			}

			$created++;
		}

		return $created;
	}

}
