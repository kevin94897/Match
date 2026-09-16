<?php
/**
 * Perfiles que acompañamos (Figma: Section — Perfiles, node 3901:5088).
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="match-sol-perfiles">
	<div class="match-sol-perfiles__card">
		<div class="match-sol-perfiles__col">
			<div>
				<p class="match-sol-perfiles__brand"><?php echo esc_html( strtolower( $args['title'] ) ); ?>®</p>
				<h2 class="match-sol-perfiles__title">
					<?php esc_html_e( 'Los perfiles que', 'match' ); ?>
					<span class="is-secondary"><?php esc_html_e( 'acompañamos.', 'match' ); ?></span>
				</h2>
			</div>
			<div class="match-sol-perfiles__scope">
				<p class="match-sol-perfiles__scope-label"><?php echo match_icon( 'plus-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Alcance', 'match' ); ?></p>
				<p class="match-sol-perfiles__scope-text">
					<strong><?php echo esc_html( $args['scope'] ); ?></strong>
					<?php echo esc_html( $args['scope_2'] ); ?>
				</p>
			</div>
		</div>

		<ul class="match-levels">
			<?php foreach ( $args['levels'] as list( $label, $photo ) ) : ?>
				<li class="match-level">
					<img src="<?php echo esc_url( $photo ); ?>" alt="" loading="lazy" decoding="async">
					<p class="match-level__label"><?php echo esc_html( $label ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
