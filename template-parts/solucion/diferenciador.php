<?php
/**
 * Casos de éxito en carrusel (Figma: Section — Diferenciador, node 4667:5502).
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
$cases = $args['cases'];
?>
<section class="match-sol-dif" data-carousel>
	<div class="match-sol-dif__box">
		<div class="match-sol-dif__track" aria-live="polite">
			<?php foreach ( $cases as $i => $case ) : ?>
				<article class="match-sol-dif__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-slide>
					<div class="match-sol-dif__left">
						<div class="match-sol-dif__logo" aria-label="<?php esc_attr_e( 'Match Executive', 'match' ); ?>" role="img">
							<?php echo file_get_contents( get_theme_file_path( 'assets/img/executive/logo-executive.svg' ) ); // phpcs:ignore ?>
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

	<?php if ( count( $cases ) > 1 ) : ?>
		<button class="match-sol-dif__arrow match-sol-dif__arrow--prev" type="button" data-prev>
			<span class="screen-reader-text"><?php esc_html_e( 'Caso anterior', 'match' ); ?></span>
			<?php echo match_icon( 'arrow-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		<button class="match-sol-dif__arrow match-sol-dif__arrow--next" type="button" data-next>
			<span class="screen-reader-text"><?php esc_html_e( 'Caso siguiente', 'match' ); ?></span>
			<?php echo match_icon( 'arrow-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		<ul class="match-dots" data-dots>
			<?php foreach ( $cases as $i => $case ) : ?>
				<li><button type="button"<?php echo 0 === $i ? ' class="is-active" aria-current="true"' : ''; ?> data-go="<?php echo (int) $i; ?>"><span class="screen-reader-text"><?php printf( esc_html__( 'Caso %d', 'match' ), $i + 1 ); ?></span></button></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
