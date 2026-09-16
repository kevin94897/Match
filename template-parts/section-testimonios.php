<?php
/**
 * Empresas que confiaron en Match (Figma: node 3502:3374).
 *
 * Contenido editorial fijo por ahora; cuando exista el CPT de testimonios se
 * reemplazan los textos por el loop.
 */
defined( 'ABSPATH' ) || exit;

$logos = match_client_logos();
unset( $logos['aenza'] );
?>
<section class="match-testimonios">
	<?php
	match_section_head(
		__( 'Bienvenido a Match', 'match' ),
		__( 'Empresas que confiaron en Match,', 'match' ),
		__( 'y encontraron al talento que buscaban', 'match' )
	);
	?>

	<div class="match-testimonios__grid">
		<div class="match-testimonios__photo">
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/testimonio-equipo.webp' ) ); ?>" alt="" loading="lazy" decoding="async">
			<span class="match-testimonios__plus" aria-hidden="true"><?php echo match_icon( 'plus-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<div class="match-testimonios__cta">
				<p>
					<strong><?php esc_html_e( 'Contactemos', 'match' ); ?></strong>
					<?php esc_html_e( 'Conversemos sobre cómo podemos atender tus requerimientos', 'match' ); ?>
				</p>
				<a class="match-btn match-btn--primary" href="#contacto">
					<?php esc_html_e( 'Contactar ahora', 'match' ); ?>
					<span class="match-btn__icon"><?php echo match_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</a>
			</div>
		</div>

		<div class="match-testimonios__body">
			<p class="match-testimonios__intro">
				<?php esc_html_e( 'No son solo palabras.', 'match' ); ?>
				<span class="is-secondary"><?php esc_html_e( 'Lo dicen las empresas que nos confiaron sus búsquedas más difíciles.', 'match' ); ?></span>
			</p>

			<div class="match-testimonios__cards">
				<blockquote class="match-testimonial">
					<div class="match-testimonial__top">
						<div class="match-testimonial__avatar">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/testimonio-avatar.png' ) ); ?>" alt="" loading="lazy" decoding="async">
						</div>
						<div>
							<p class="match-testimonial__quote"><?php esc_html_e( '“Tengo el mejor de los conceptos del servicio de Match”', 'match' ); ?></p>
							<p class="match-testimonial__text"><?php esc_html_e( 'Es inclusive mucho mejor que otras consultoras transnacionales, tienen real conocimiento del mercado. Sus candidatos mejoraron mis expectativas referente a su competencia.', 'match' ); ?></p>
						</div>
					</div>
					<footer class="match-testimonial__author">
						<p class="match-testimonial__name"><?php esc_html_e( 'Cristopher Cardamo', 'match' ); ?></p>
						<p class="match-testimonial__role">
							<span><?php esc_html_e( 'Global Project Manager', 'match' ); ?></span>
							<span><?php esc_html_e( 'Alfa Laval, Suecia', 'match' ); ?></span>
						</p>
					</footer>
				</blockquote>

				<div class="match-testimonios__side">
					<div class="match-card match-testimonios__note">
						<p class="match-testimonios__note-title"><?php esc_html_e( 'Nuestros profesionales hoy trabajan en', 'match' ); ?></p>
						<p class="match-testimonios__note-text"><?php esc_html_e( 'Compañías líderes que confiaron sus posiciones clave a Match.', 'match' ); ?></p>
					</div>
					<ul class="match-card match-logos">
						<?php foreach ( $logos as $slug => $name ) : ?>
							<li><img src="<?php echo esc_url( get_theme_file_uri( "assets/img/logos/{$slug}.png" ) ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy" decoding="async"></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
