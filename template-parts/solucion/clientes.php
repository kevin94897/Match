<?php
/**
 * Grilla de clientes (Figma: Section — Clientes, node 3765:6892).
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
$logos = match_client_logos();
?>
<section class="match-sol-clientes">
	<div class="match-sol-clientes__head" data-aos="fade-up">
		<p class="match-sol-clientes__label"><?php echo match_icon( 'plus-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Nuestros clientes', 'match' ); ?></p>
		<p class="match-sol-clientes__years"><?php echo esc_html( $args['years'] ); ?></p>
	</div>
	<ul class="match-sol-clientes__grid">
		<?php foreach ( $args['clients'] as $i => $slug ) : ?>
			<li data-aos="fade-up" data-aos-delay="<?php echo (int) ( ( $i % 3 ) * 100 ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( "assets/img/logos/{$slug}.png" ) ); ?>" alt="<?php echo esc_attr( $logos[ $slug ] ?? $slug ); ?>" loading="lazy" decoding="async"></li>
		<?php endforeach; ?>
	</ul>
</section>
