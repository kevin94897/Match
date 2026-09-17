<?php
/**
 * Hero de la solución (Figma: Interna — Executive, node 3254:3).
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
$logos = match_client_logos();
?>
<section class="match-sol-hero">
	<h1 class="match-sol-hero__title" data-aos="fade-up"><?php echo esc_html( $args['title'] ); ?>.</h1>

	<div class="match-sol-hero__lead" data-aos="fade-up" data-aos-delay="150">
		<p class="match-sol-hero__kicker">
			<?php echo match_icon( 'plus-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo esc_html( $args['kicker'] ); ?>
		</p>
		<p class="match-sol-hero__text">
			<strong><?php echo esc_html( $args['lead'] ); ?></strong>
			<?php echo esc_html( $args['lead_2'] ); ?>
		</p>
	</div>

	<div class="match-trust" data-aos="fade-up" data-aos-delay="300">
		<div class="match-trust__chips">
			<?php foreach ( $args['trust'] as $slug ) : ?>
				<span class="match-trust__chip"><img src="<?php echo esc_url( get_theme_file_uri( "assets/img/logos/{$slug}.png" ) ); ?>" alt="<?php echo esc_attr( $logos[ $slug ] ?? $slug ); ?>" loading="lazy" decoding="async"></span>
			<?php endforeach; ?>
			<span class="match-trust__chip match-trust__chip--more">10+</span>
		</div>
		<p class="match-trust__text"><?php esc_html_e( 'Respaldado por', 'match' ); ?> <strong><?php esc_html_e( 'líderes de la industria', 'match' ); ?></strong></p>
	</div>
</section>
