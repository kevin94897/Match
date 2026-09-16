<?php
/**
 * Pie de página.
 *
 * Figma: Section — Footer (node 3570:79). Bloque de contacto, tres columnas
 * de enlaces, wordmark grande y barra legal.
 */
defined( 'ABSPATH' ) || exit;

$phone = get_theme_mod( 'match_phone', '(+51) 908825057' );
$email = get_theme_mod( 'match_email', 'conversemos@match.win' );
$ruc   = get_theme_mod( 'match_ruc', '20508540788' );
$razon = get_theme_mod( 'match_razon_social', 'MATCH CONSULTORES SAC' );

$columns = array(
	'footer_jobs'   => __( 'Job Board', 'match' ),
	'footer_nav'    => __( 'Navegación', 'match' ),
	'footer_social' => __( 'Síguenos', 'match' ),
);
?>
<footer class="match-footer">
	<div class="match-footer__top">
		<div class="match-footer__row">
			<div class="match-footer__contact">
				<div class="match-footer__lines">
					<p class="match-footer__line match-footer__phone">
						<?php echo match_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
					</p>
					<p class="match-footer__line match-footer__email">
						<?php echo match_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
					</p>
				</div>

				<dl class="match-footer__legal">
					<div><dt><?php esc_html_e( 'RUC', 'match' ); ?></dt><dd><?php echo esc_html( $ruc ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Razón Social', 'match' ); ?></dt><dd><?php echo esc_html( $razon ); ?></dd></div>
				</dl>
			</div>

			<nav class="match-footer__nav" aria-label="<?php esc_attr_e( 'Navegación del pie', 'match' ); ?>">
				<?php foreach ( $columns as $location => $label ) : ?>
					<div class="match-footer__column">
						<p class="match-footer__column-title"><?php echo esc_html( $label ); ?></p>
						<?php match_footer_menu( $location ); ?>
					</div>
				<?php endforeach; ?>
			</nav>
		</div>

		<p class="match-footer__wordmark">
			<?php echo match_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</p>
	</div>

	<div class="match-footer__bar">
		<div class="match-footer__bar-inner">
			<p>
				<?php
				printf(
					/* translators: %s: año */
					esc_html__( '© %s Match. Todos los derechos reservados.', 'match' ),
					esc_html( gmdate( 'Y' ) )
				);
				?>
			</p>
			<p class="match-footer__policies">
				<a href="<?php echo esc_url( home_url( '/politica-de-privacidad/' ) ); ?>"><?php esc_html_e( 'Política de Privacidad', 'match' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/terminos-de-servicio/' ) ); ?>"><?php esc_html_e( 'Términos de Servicio', 'match' ); ?></a>
			</p>
		</div>
	</div>
</footer>
