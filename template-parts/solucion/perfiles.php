<?php
/**
 * Perfiles que acompañamos (Figma: Section — Perfiles, node 3901:5088).
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="match-sol-perfiles">
	<div class="match-sol-perfiles__card" data-aos="fade-up">
		<div class="match-sol-perfiles__col">
			<div>
				<p class="match-sol-perfiles__brand"><?php echo esc_html( strtolower( $args['title'] ) ); ?>®</p>
				<h2 class="match-sol-perfiles__title">
					<?php echo esc_html( $args['levels_title'] ); ?>
					<span class="is-secondary"><?php echo esc_html( $args['levels_title_2'] ); ?></span>
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

		<ul class="match-levels<?php echo count( $args['levels'] ) <= 2 ? ' match-levels--stack' : ''; ?>">
			<?php foreach ( $args['levels'] as $i => list( $label, $photo ) ) : ?>
				<li class="match-level" data-aos="fade-up" data-aos-delay="<?php echo (int) ( 150 + $i * 100 ); ?>">
					<img src="<?php echo esc_url( $photo ); ?>" alt="" loading="lazy" decoding="async">
					<?php if ( $args['levels_icon'] ) : ?>
						<span class="match-level__icon"><?php echo match_icon( $args['levels_icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php endif; ?>
					<p class="match-level__label"><?php echo esc_html( $label ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
