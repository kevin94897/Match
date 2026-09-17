<?php
/**
 * Métricas sobre foto (Figma: Section — Métricas, node 3765:6834).
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="match-sol-metricas">
	<div class="match-sol-metricas__photo" data-aos="fade-up">
		<img src="<?php echo esc_url( $args['metrics_photo'] ); ?>" alt="" loading="lazy" decoding="async">
		<ul class="match-sol-metricas__row">
			<?php foreach ( $args['metrics'] as $i => list( $value, $label ) ) : ?>
				<li data-aos="fade-up" data-aos-delay="<?php echo (int) ( 200 + $i * 120 ); ?>">
					<span class="match-sol-metricas__value"><?php echo esc_html( $value ); ?></span>
					<span class="match-sol-metricas__label"><?php echo esc_html( $label ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
