<?php
/**
 * Aviso flotante "Agendar consultoría" (Figma: Toast-Consultoria, node 4683:7406).
 *
 * @var array $args
 */
defined( 'ABSPATH' ) || exit;
?>
<aside class="match-toast" id="match-toast" role="complementary" aria-label="<?php esc_attr_e( 'Agendar consultoría', 'match' ); ?>" hidden>
	<div class="match-toast__head">
		<div class="match-toast__brand">
			<span class="match-toast__logo" aria-hidden="true"><?php echo match_icon( 'isotipo' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<p class="match-toast__title"><?php echo esc_html( $args['toast'] ); ?></p>
		</div>
		<button class="match-toast__close" type="button" data-toast-close>
			<span class="screen-reader-text"><?php esc_html_e( 'Cerrar', 'match' ); ?></span>
			<?php echo match_icon( 'close-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
	</div>
	<a class="match-btn match-btn--primary" href="#contacto">
		<span class="match-btn__orbit" aria-hidden="true"></span>
		<?php esc_html_e( 'Agendar consultoría', 'match' ); ?>
		<span class="match-btn__icon"><?php echo match_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	</a>
</aside>
