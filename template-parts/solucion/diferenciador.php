<?php
/**
 * Casos de éxito en carrusel (Figma: Section — Diferenciador, node 4667:5502).
 * Carrusel con Embla (ver [data-embla] en assets/js/app.js); la maqueta del
 * slide sigue en solucion.css y el layout del carrusel en utilidades Tailwind.
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
$cases = $args['cases'];
?>
<section class="match-sol-dif" data-embla data-embla-options="<?php echo esc_attr( wp_json_encode( array( 'loop' => true ) ) ); ?>">
	<div class="match-sol-dif__box" data-aos="fade-up">
		<div class="overflow-hidden" data-embla-viewport>
			<div class="flex touch-pan-y">
				<?php foreach ( $cases as $i => $case ) : ?>
					<article class="match-sol-dif__slide min-w-0 shrink-0 grow-0 basis-full">
						<div class="match-sol-dif__left">
							<div class="match-sol-dif__logo" aria-label="<?php echo esc_attr( 'Match ' . $args['title'] ); ?>" role="img">
								<?php echo is_readable( $args['logo'] ) ? file_get_contents( $args['logo'] ) : ''; // phpcs:ignore ?>
							</div>
							<?php if ( $case['stats'] ) : ?>
								<dl class="match-sol-dif__stats">
									<?php foreach ( $case['stats'] as list( $value, $label ) ) : ?>
										<div><dt><?php echo esc_html( $value ); ?></dt><dd><?php echo esc_html( $label ); ?></dd></div>
									<?php endforeach; ?>
								</dl>
							<?php endif; ?>
						</div>
						<div class="match-sol-dif__right">
							<div>
								<p class="match-sol-dif__quote"><?php echo esc_html( $case['quote'] ); ?></p>
								<p class="match-sol-dif__text"><?php echo esc_html( $case['text'] ); ?></p>
							</div>
							<div class="match-sol-dif__attrib">
								<span class="match-sol-dif__client">
									<?php if ( $case['logo'] ) : ?>
										<img src="<?php echo esc_url( $case['logo'] ); ?>" alt="" loading="lazy" decoding="async">
									<?php else : ?>
										<?php echo match_icon( 'isotipo' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									<?php endif; ?>
								</span>
								<div>
									<p class="match-sol-dif__name"><?php echo esc_html( $case['role'] ); ?></p>
									<p class="match-sol-dif__company"><?php echo esc_html( $case['company'] ); ?></p>
								</div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<?php if ( count( $cases ) > 1 ) : ?>
		<button class="match-sol-dif__arrow match-sol-dif__arrow--prev" type="button" data-embla-prev>
			<span class="screen-reader-text"><?php esc_html_e( 'Caso anterior', 'match' ); ?></span>
			<?php echo match_icon( 'arrow-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		<button class="match-sol-dif__arrow match-sol-dif__arrow--next" type="button" data-embla-next>
			<span class="screen-reader-text"><?php esc_html_e( 'Caso siguiente', 'match' ); ?></span>
			<?php echo match_icon( 'arrow-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		<ul class="match-dots" data-embla-dots data-label="<?php esc_attr_e( 'Ir al caso', 'match' ); ?>"></ul>
	<?php endif; ?>
</section>
