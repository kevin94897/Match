<?php
/**
 * Testimonios (Figma: node 3847:1814).
 * Carrusel con Embla: en escritorio (≥1441 px) las tres tarjetas caben y no
 * hay desplazamiento; por debajo se deslizan de a una.
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="match-sol-testimonios">
	<?php match_section_head( __( 'Historias reales', 'match' ), __( 'Testimonios.', 'match' ), '', 'h2-xl' ); ?>

	<div class="flex flex-col gap-4" data-embla data-aos="fade-up" data-aos-delay="100">
		<div class="overflow-hidden" data-embla-viewport>
			<div class="flex touch-pan-y gap-4">
				<?php foreach ( $args['reviews'] as $review ) : ?>
					<blockquote class="match-review min-w-0 shrink-0 grow-0 basis-full sm:basis-[calc(50%-8px)] lg:basis-[calc((100%-692px)/2)]">
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

				<div class="match-review match-review--dark min-w-0 shrink-0 grow-0 basis-full sm:basis-[calc(50%-8px)] lg:basis-[660px]">
					<img src="<?php echo esc_url( $args['reviews_photo'] ); ?>" alt="" loading="lazy" decoding="async">
					<p class="match-review__headline"><?php esc_html_e( '¿Qué dicen nuestros clientes?', 'match' ); ?></p>
				</div>
			</div>
		</div>

		<ul class="match-dots" data-embla-dots data-label="<?php esc_attr_e( 'Ir al testimonio', 'match' ); ?>"></ul>
	</div>
</section>
