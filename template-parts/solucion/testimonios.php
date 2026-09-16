<?php
/**
 * Testimonios (Figma: node 3847:1814).
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="match-sol-testimonios">
	<?php match_section_head( __( 'Historias reales', 'match' ), __( 'Testimonios.', 'match' ), '', 'h2-xl' ); ?>

	<div class="match-sol-testimonios__wrap">
		<div class="match-sol-testimonios__row">
			<?php foreach ( $args['reviews'] as $review ) : ?>
				<blockquote class="match-review">
					<div class="match-review__photo<?php echo 'contain' === $review['fit'] ? ' match-review__photo--contain' : ''; ?>" style="--bg: <?php echo esc_attr( $review['bg'] ); ?>">
						<img src="<?php echo esc_url( $review['photo'] ); ?>" alt="" loading="lazy" decoding="async">
					</div>
					<div class="match-review__body">
						<p class="match-review__text"><?php echo esc_html( $review['text'] ); ?></p>
						<hr>
						<footer>
							<p class="match-review__name"><?php echo esc_html( $review['name'] ); ?></p>
							<p class="match-review__role"><?php echo esc_html( $review['role'] ); ?></p>
						</footer>
					</div>
				</blockquote>
			<?php endforeach; ?>

			<div class="match-review match-review--dark">
				<img src="<?php echo esc_url( $args['reviews_photo'] ); ?>" alt="" loading="lazy" decoding="async">
				<p class="match-review__headline"><?php esc_html_e( '¿Qué dicen nuestros clientes?', 'match' ); ?></p>
			</div>
		</div>

		<ul class="match-dots" aria-hidden="true">
			<li><button type="button" class="is-active" tabindex="-1"></button></li>
			<li><button type="button" tabindex="-1"></button></li>
			<li><button type="button" tabindex="-1"></button></li>
		</ul>
	</div>
</section>
