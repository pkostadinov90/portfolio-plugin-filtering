<?php
/**
 * Results template.
 *
 * Inherited variables from Filtering\Ajax\Results::render_result().
 *
 * @var int    $post_id
 * @var string $title
 * @var string $callout
 * @var string $review_link
 * @var string $review_title
 * @var string $promo_code
 * @var mixed  $slot_count
 * @var mixed  $vip
 * @var mixed  $packages
 * @var string $casino_link
 * @var string $choices_html
 */

?>

<div class="rt-results-content">
	<div class="rt-results-content-item">
		<strong class="rt-statistic-text-primary">
			<?php echo esc_html( $title ); ?>
		</strong>

		<?php if ( ! empty( $callout ) ) : ?>
			<p class="rt-details"><?php echo esc_html( $callout ); ?></p>
		<?php endif; ?>
	</div>

	<div class="rt-results-content-item">
		<span><?php esc_html_e( 'This casino is best for:', 'filtering' ); ?></span>
		<div class="rt-quiz-choices js-rt-quiz-choices">
			<?php echo $choices_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</div>

<div class="rt-results-data">
	<div class="filtering-result-card">
		<h3 class="filtering-result-title"><?php echo esc_html( $title ); ?></h3>

		<ul class="filtering-result-meta">
			<?php if ( ! empty( $slot_count ) ) : ?>
				<li>
					<?php echo esc_html( sprintf( __( 'Slot games: %s', 'filtering' ), $slot_count ) ); ?>
				</li>
			<?php endif; ?>

			<li>
				<?php
				echo esc_html(
					sprintf(
						__( 'VIP: %s', 'filtering' ),
						$vip ? __( 'Yes', 'filtering' ) : __( 'No', 'filtering' )
					)
				);
				?>
			</li>
			<li>
				<?php
				echo esc_html(
					sprintf(
						__( 'Packages: %s', 'filtering' ),
						$packages ? __( 'Yes', 'filtering' ) : __( 'No', 'filtering' )
					)
				);
				?>
			</li>

			<?php if ( ! empty( $promo_code ) ) : ?>
				<li>
					<?php echo esc_html( sprintf( __( 'Promo code: %s', 'filtering' ), $promo_code ) ); ?>
				</li>
			<?php endif; ?>
		</ul>

		<?php if ( ! empty( $review_link ) && ! empty( $review_title ) ) : ?>
			<p class="filtering-result-review">
				<a href="<?php echo esc_url( $review_link ); ?>">
					<?php echo esc_html( $review_title ); ?>
				</a>
			</p>
		<?php endif; ?>

		<?php if ( ! empty( $casino_link ) ) : ?>
			<p class="filtering-result-cta">
				<a class="button" href="<?php echo esc_url( $casino_link ); ?>">
					<?php esc_html_e( 'Visit casino', 'filtering' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</div>
