<?php
/**
 * Newsletter del blog (Figma: Newslette, node 3514:5563).
 *
 * El formulario lo procesa match_handle_newsletter() (admin-post) y avisa por
 * correo al equipo.
 */
defined( 'ABSPATH' ) || exit;

$status = isset( $_GET['newsletter'] ) ? sanitize_key( wp_unslash( $_GET['newsletter'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
?>
<section class="match-newsletter" id="newsletter">
	<div class="match-newsletter__row">
		<div class="match-newsletter__left" data-aos="fade-up">
			<h2 class="match-newsletter__lead">
				<?php esc_html_e( 'Las mejores decisiones de talento', 'match' ); ?>
				<span class="is-muted"><?php esc_html_e( 'empiezan por estar bien informado', 'match' ); ?></span>
			</h2>
			<div class="match-newsletter__bullet">
				<span class="match-newsletter__plus" aria-hidden="true"><?php echo match_icon( 'plus-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<div>
					<p class="match-newsletter__bullet-title"><?php esc_html_e( 'Únete a nuestra red', 'match' ); ?></p>
					<p class="match-newsletter__bullet-text"><?php esc_html_e( 'Recibe historias, tendencias y aprendizajes directamente en tu correo.', 'match' ); ?></p>
				</div>
			</div>
		</div>

		<form class="match-newsletter__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-aos="fade-up" data-aos-delay="150">
			<input type="hidden" name="action" value="match_newsletter">
			<?php wp_nonce_field( 'match_newsletter', 'match_newsletter_nonce' ); ?>
			<input type="text" name="match_web" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px">

			<p class="match-newsletter__title"><?php esc_html_e( 'Newsletter', 'match' ); ?></p>

			<div class="match-newsletter__fields">
				<label class="screen-reader-text" for="match-nl-nombre"><?php esc_html_e( 'Nombre', 'match' ); ?></label>
				<input class="match-newsletter__input" id="match-nl-nombre" type="text" name="nombre" autocomplete="name" placeholder="<?php esc_attr_e( 'Tu nombre...', 'match' ); ?>">
				<label class="screen-reader-text" for="match-nl-correo"><?php esc_html_e( 'Correo electrónico', 'match' ); ?></label>
				<input class="match-newsletter__input" id="match-nl-correo" type="email" name="correo" autocomplete="email" required placeholder="<?php esc_attr_e( 'Tu correo electrónico', 'match' ); ?>">
			</div>

			<?php if ( 'ok' === $status ) : ?>
				<p class="match-newsletter__notice" role="status"><?php esc_html_e( 'Listo, te sumamos a la lista.', 'match' ); ?></p>
			<?php elseif ( 'error' === $status ) : ?>
				<p class="match-newsletter__notice match-newsletter__notice--error" role="alert"><?php esc_html_e( 'No pudimos registrarte. Revisa tu correo e inténtalo de nuevo.', 'match' ); ?></p>
			<?php endif; ?>

			<button class="match-btn match-btn--primary match-newsletter__submit" type="submit"><?php esc_html_e( 'Suscribirme', 'match' ); ?></button>
		</form>
	</div>
</section>
