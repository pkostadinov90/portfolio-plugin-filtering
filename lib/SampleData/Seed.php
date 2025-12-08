<?php
/**
 * Manual sample data seeding flow.
 *
 * Shows an admin notice after activation (if needed) with a button to seed terms.
 * Assumes CPT/taxonomies are registered elsewhere in the plugin.
 *
 * @package Filtering
 */

namespace Filtering\SampleData;

class Seed {

	/**
	 * Option flag set on activation.
	 *
	 * @var string
	 */
	private $seed_flag_option = 'filtering_needs_manual_seeding';

	/**
	 * Casino post type slug.
	 *
	 * @var string
	 */
	private $casino_post_type = 'filtering_casino';

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_notices', array( $this, 'maybe_show_notice' ) );
		add_action( 'admin_post_filtering_seed_terms', array( $this, 'handle_seed_terms' ) );
	}

	/**
	 * Show notice with seed button if flag is set and CPT has no posts.
	 *
	 * @return void
	 */
	public function maybe_show_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! get_option( $this->seed_flag_option ) ) {
			return;
		}

		// If CPT already has content, clear flag and stop.
		if ( $this->casino_has_any_posts() ) {
			delete_option( $this->seed_flag_option );
			return;
		}

		$action_url = add_query_arg(
			array(
				'action' => 'filtering_seed_terms',
			),
			admin_url( 'admin-post.php' )
		);

		$action_url = wp_nonce_url( $action_url, 'filtering_seed_terms' );

		?>
		<div class="notice notice-info">
			<p>
				<?php esc_html_e( 'Filtering: No Casino entries found. You can seed default taxonomy terms to get started.', 'filtering' ); ?>
			</p>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( $action_url ); ?>">
					<?php esc_html_e( 'Seed Default Terms', 'filtering' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Handle seeding action from the admin notice button.
	 *
	 * @return void
	 */
	public function handle_seed_terms() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'filtering' ) );
		}

		check_admin_referer( 'filtering_seed_terms' );

		// If content exists, do nothing and clear flag.
		if ( $this->casino_has_any_posts() ) {
			delete_option( $this->seed_flag_option );
			wp_safe_redirect( admin_url() );
			exit;
		}

		$terms = new Terms();
		$terms->insert_terms();
		$terms = new Posts();
		$terms->insert_posts();

		// Clear the flag so the notice doesn't reappear.
		delete_option( $this->seed_flag_option );

		wp_safe_redirect( admin_url( 'edit.php?post_type=' . $this->casino_post_type ) );
		exit;
	}

	/**
	 * Check if the Casino CPT has at least one post.
	 *
	 * @return bool
	 */
	private function casino_has_any_posts() {
		$query = new \WP_Query(
			array(
				'post_type'      => $this->casino_post_type,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);

		return ! empty( $query->posts );
	}

}
