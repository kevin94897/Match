<?php
/**
 * Hablemos (Figma: Section — Contacto, node 3502:3508).
 *
 * El formulario lo procesa match_handle_contact() (admin-post) y envía el
 * mensaje al correo del Personalizador.
 */
defined( 'ABSPATH' ) || exit;

$status  = isset( $_GET['contacto'] ) ? sanitize_key( wp_unslash( $_GET['contacto'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$profile = isset( $_GET['perfil'] ) && 'profesional' === $_GET['perfil'] ? 'profesional' : 'empresa'; // phpcs:ignore WordPress.Security.NonceVerification

// Las internas pasan su propio texto de apoyo.
$lead       = $args['lead'] ?? __( 'Cuéntanos qué necesitas', 'match' );
$lead_muted = array_key_exists( 'lead_muted', (array) $args ) ? $args['lead_muted'] : __( '— ya sea headhunting, evaluación de talento u outplacement.', 'match' );
?>
<section class="match-contacto" id="contacto">
	<div class="match-contacto__inner">
		<div class="match-contacto__bg" aria-hidden="true">
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/contacto-bg.webp' ) ); ?>" alt="" loading="lazy" decoding="async">
		</div>

		<form class="match-glass match-contacto__card" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="match_contact">
			<?php wp_nonce_field( 'match_contact', 'match_contact_nonce' ); ?>
			<input type="text" name="match_web" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px">

			<div class="match-toggle">
				<span class="match-toggle__label" id="match-perfil-label"><?php esc_html_e( 'Selecciona tu perfil', 'match' ); ?></span>
				<div class="match-toggle__group" role="radiogroup" aria-labelledby="match-perfil-label">
					<button class="match-toggle__opt<?php echo 'empresa' === $profile ? ' is-active' : ''; ?>" type="button" role="radio" aria-checked="<?php echo 'empresa' === $profile ? 'true' : 'false'; ?>" data-value="empresa">
						<?php esc_html_e( 'Empresa', 'match' ); ?>
						<?php echo match_icon( 'buildings' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
					<button class="match-toggle__opt<?php echo 'profesional' === $profile ? ' is-active' : ''; ?>" type="button" role="radio" aria-checked="<?php echo 'profesional' === $profile ? 'true' : 'false'; ?>" data-value="profesional">
						<?php esc_html_e( 'Profesional', 'match' ); ?>
						<?php echo match_icon( 'user-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
				</div>
				<input type="hidden" name="perfil" value="<?php echo esc_attr( $profile ); ?>">
			</div>

			<div class="match-contacto__fields">
				<label class="match-field">
					<span class="match-field__label"><?php esc_html_e( 'Nombre completo', 'match' ); ?></span>
					<input class="match-field__input" type="text" name="nombre" placeholder="<?php esc_attr_e( 'Escribe tu nombre', 'match' ); ?>" required autocomplete="name">
				</label>
				<label class="match-field">
					<span class="match-field__label"><?php esc_html_e( 'Correo electrónico', 'match' ); ?></span>
					<input class="match-field__input" type="email" name="correo" placeholder="tu@empresa.com" required autocomplete="email">
				</label>
				<label class="match-field">
					<span class="match-field__label"><?php esc_html_e( 'Mensaje', 'match' ); ?></span>
					<textarea class="match-field__input" name="mensaje" placeholder="<?php esc_attr_e( 'Cuéntanos sobre el rol y el perfil que buscas.', 'match' ); ?>" required></textarea>
				</label>
			</div>

			<div class="match-contacto__submit">
				<?php if ( 'ok' === $status ) : ?>
					<p class="match-contacto__notice" role="status"><?php esc_html_e( 'Gracias. Te responderemos a la brevedad.', 'match' ); ?></p>
				<?php elseif ( 'error' === $status ) : ?>
					<p class="match-contacto__notice match-contacto__notice--error" role="alert"><?php esc_html_e( 'No pudimos enviar tu mensaje. Revisa los datos e inténtalo de nuevo.', 'match' ); ?></p>
				<?php endif; ?>
				<button class="match-btn match-btn--primary match-btn--block" type="submit">
					<?php esc_html_e( 'Enviar mensaje', 'match' ); ?>
					<span class="match-btn__icon"><?php echo match_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</button>
				<p class="match-contacto__legal"><?php esc_html_e( 'Al enviar, aceptas nuestros Términos y Política de Privacidad.', 'match' ); ?></p>
			</div>
		</form>

		<div class="match-contacto__text">
			<h2 class="match-contacto__title"><?php esc_html_e( 'Hablemos.', 'match' ); ?></h2>
			<p class="match-contacto__lead">
				<?php echo esc_html( $lead ); ?>
				<?php if ( $lead_muted ) : ?>
					<span class="is-muted"><?php echo esc_html( $lead_muted ); ?></span>
				<?php endif; ?>
			</p>
			<hr class="match-contacto__rule">
			<div class="match-contacto__bullets">
				<div class="match-bullet">
					<p class="match-bullet__head"><?php echo match_icon( 'lightning' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Respuesta rápida.', 'match' ); ?></p>
					<p class="match-bullet__text"><?php esc_html_e( 'Si estás listo para encontrar al candidato ideal, nos encantaría conversar.', 'match' ); ?></p>
				</div>
				<div class="match-bullet">
					<p class="match-bullet__head"><?php echo match_icon( 'compass' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Próximos pasos claros.', 'match' ); ?></p>
					<p class="match-bullet__text"><?php esc_html_e( 'Después de la consulta, te daremos un plan detallado y un cronograma.', 'match' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>
