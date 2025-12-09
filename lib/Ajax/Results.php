<?php
/**
 * AJAX Results handler.
 *
 * Handles the Filtering recommendation tool results rendering.
 *
 * Expected POST:
 * - action=filtering_get_result
 * - selections=<json>   // object: key => value
 * - _ajax_nonce=<nonce> // nonce created for 'filtering_get_result'
 *
 * Returns HTML that will replace the entire results step content.
 *
 * Matching logic:
 * - "Intelligent" best-match selection.
 * - Finds posts matching ANY of the chosen terms (OR).
 * - Scores each candidate by how many of the selected taxonomy terms it matches.
 * - Returns the post with the highest score.
 *
 * @package Filtering
 */

namespace Filtering\Ajax;

class Results {

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	private $post_type = 'filtering_casino';

	/**
	 * Map JS selection keys to taxonomy slugs.
	 *
	 * Confirmed mapping:
	 * - Games   -> filtering_type
	 * - Dealers -> filtering_game
	 * - Speed   -> filtering_banking
	 * - Devices -> filtering_payouts
	 *
	 * @var array<string, string>
	 */
	private $key_to_taxonomy = array(
		'Games'   => 'filtering_type',
		'Dealers' => 'filtering_game',
		'Speed'   => 'filtering_banking',
		'Devices' => 'filtering_payouts',
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_filtering_get_result', array( $this, 'handle' ) );
		add_action( 'wp_ajax_nopriv_filtering_get_result', array( $this, 'handle' ) );
	}

	/**
	 * Handle AJAX request.
	 *
	 * @return void
	 */
	public function handle() {
		check_ajax_referer( 'filtering_get_result' );

		$selections = $this->get_selections_from_request();
		$filters    = $this->normalize_filters( $selections );

		$best_post_id = $this->find_best_match_post_id( $filters );

		if ( empty( $best_post_id ) ) {
			echo $this->render_no_results( $filters ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die();
		}

		echo $this->render_result( $best_post_id, $filters ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_die();
	}

	/**
	 * Get selections JSON from request.
	 *
	 * @return array<string, string>
	 */
	private function get_selections_from_request() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$raw = isset( $_POST['selections'] ) ? wp_unslash( $_POST['selections'] ) : '';

		if ( empty( $raw ) ) {
			return array();
		}

		$decoded = json_decode( $raw, true );

		if ( ! is_array( $decoded ) ) {
			return array();
		}

		$clean = array();

		foreach ( $decoded as $key => $value ) {
			if ( ! is_string( $key ) || ! is_string( $value ) ) {
				continue;
			}

			$key   = sanitize_text_field( $key );
			$value = sanitize_text_field( $value );

			if ( '' === $key || '' === $value ) {
				continue;
			}

			$clean[ $key ] = $value;
		}

		return $clean;
	}

	/**
	 * Normalize raw selections into taxonomy => term_name filters.
	 *
	 * @param array<string, string> $selections Raw selections keyed by JS key.
	 * @return array<string, string> Taxonomy => term name.
	 */
	private function normalize_filters( $selections ) {
		$filters = array();

		foreach ( $selections as $key => $value ) {
			if ( ! isset( $this->key_to_taxonomy[ $key ] ) ) {
				continue;
			}

			$taxonomy = $this->key_to_taxonomy[ $key ];

			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$filters[ $taxonomy ] = $value;
		}

		return $filters;
	}

	/**
	 * Find the best matching post ID based on number of term matches.
	 *
	 * Strategy:
	 * 1) Query candidate posts that match ANY selected term (OR across taxonomies).
	 * 2) Score each candidate by counting how many selected terms it matches.
	 * 3) Return the post with highest score.
	 *
	 * @param array<string, string> $filters Taxonomy => term name.
	 * @return int
	 */
	private function find_best_match_post_id( $filters ) {
		if ( empty( $filters ) ) {
			return 0;
		}

		$candidate_ids = $this->get_candidate_post_ids( $filters );

		if ( empty( $candidate_ids ) ) {
			return 0;
		}

		$best_id    = 0;
		$best_score = 0;

		foreach ( $candidate_ids as $post_id ) {
			$score = $this->calculate_match_score( (int) $post_id, $filters );

			if ( $score > $best_score ) {
				$best_score = $score;
				$best_id    = (int) $post_id;

				// Perfect match: no need to keep searching.
				if ( $best_score >= count( $filters ) ) {
					break;
				}
			}
		}

		return $best_id;
	}

	/**
	 * Get candidate post IDs that match ANY selected term.
	 *
	 * @param array<string, string> $filters Taxonomy => term name.
	 * @return int[]
	 */
	private function get_candidate_post_ids( $filters ) {
		$tax_query = array( 'relation' => 'OR' );

		foreach ( $filters as $taxonomy => $term_name ) {
			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'name',
				'terms'    => array( $term_name ),
			);
		}

		$query = new \WP_Query(
			array(
				'post_type'      => $this->post_type,
				'post_status'    => 'publish',
				'posts_per_page' => 200,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'tax_query'      => $tax_query,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		if ( empty( $query->posts ) ) {
			return array();
		}

		return array_map( 'intval', $query->posts );
	}

	/**
	 * Calculate how many selected taxonomy terms a post matches.
	 *
	 * @param int                  $post_id Post ID.
	 * @param array<string, string> $filters Taxonomy => term name.
	 * @return int
	 */
	private function calculate_match_score( $post_id, $filters ) {
		$score = 0;

		foreach ( $filters as $taxonomy => $term_name ) {
			if ( has_term( $term_name, $taxonomy, $post_id ) ) {
				$score++;
			}
		}

		return $score;
	}

	/**
	 * Render successful result HTML.
	 *
	 * @param int                  $post_id Post ID.
	 * @param array<string, string> $filters Taxonomy => term name.
	 * @return string
	 */
	private function render_result( $post_id, $filters ) {
		$title = get_the_title( $post_id );

		$callout      = get_post_meta( $post_id, '_filtering_casino_callout', true );
		$review_link  = get_post_meta( $post_id, '_filtering_casino_review_link', true );
		$review_title = get_post_meta( $post_id, '_filtering_casino_review_title', true );
		$promo_code   = get_post_meta( $post_id, '_filtering_casino_promo_code', true );
		$slot_count   = get_post_meta( $post_id, '_filtering_casino_slot_count', true );
		$vip          = get_post_meta( $post_id, '_filtering_casino_vip', true );
		$packages     = get_post_meta( $post_id, '_filtering_casino_packages', true );
		$casino_link  = get_post_meta( $post_id, '_filtering_casino_link', true );

		$choices_html = $this->render_choices( $filters );

		ob_start();

		$template = FILTERING_DIR . '/templates/results.php';
		if ( file_exists( $template ) ) {
			include $template;
		}

		return ob_get_clean();
	}

	/**
	 * Render no results HTML.
	 *
	 * @param array<string, string> $filters Taxonomy => term name.
	 * @return string
	 */
	private function render_no_results( $filters ) {
		$choices_html = $this->render_choices( $filters );

		ob_start();
		?>
		<div class="rt-results-nodata js-rt-results-nodata">
			<p><?php esc_html_e( "Couldn't find any matches for you, please try again.", 'filtering' ); ?></p>

			<?php if ( ! empty( $choices_html ) ) : ?>
				<div class="rt-quiz-choices js-rt-quiz-choices">
					<?php echo $choices_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render choice badges from filters.
	 *
	 * @param array<string, string> $filters Taxonomy => term name.
	 * @return string
	 */
	private function render_choices( $filters ) {
		if ( empty( $filters ) ) {
			return '';
		}

		$html = '';

		foreach ( $filters as $taxonomy => $term_name ) {
			$html .= sprintf(
				'<div class="rt-quiz-choice-item" data-taxonomy="%s">%s</div>',
				esc_attr( $taxonomy ),
				esc_html( $term_name )
			);
		}

		return $html;
	}
}
