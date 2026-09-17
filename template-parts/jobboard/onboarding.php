<?php
/**
 * Modal de bienvenida del panel (Figma: Modal — Onboarding, node 4409:159).
 *
 * Se pinta solo la primera vez que un usuario entra al panel
 * (match_jobboard_show_onboarding). Al cerrarlo, app.js lo marca por AJAX
 * en user meta para no repetirlo.
 */
defined( 'ABSPATH' ) || exit;

$steps = array(
	array( __( 'Tu CV es necesario para postularte', 'match' ), __( 'Sin CV activo no puedes postularte.', 'match' ) ),
	array( __( 'Postúlate en segundos', 'match' ), __( 'Con CV listo, postularte es un clic.', 'match' ) ),
	array( __( 'Mantén tu CV actualizado', 'match' ), __( 'Reemplázalo cuando quieras desde tu perfil.', 'match' ) ),
);
?>
<dialog class="match-jb-onboarding" aria-labelledby="match-jb-onboarding-title" data-onboarding data-onboarding-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-onboarding-nonce="<?php echo esc_attr( wp_create_nonce( 'match_onboarding' ) ); ?>" data-lenis-prevent>
	<div class="match-jb-onboarding__card" tabindex="-1" autofocus>
		<div class="match-jb-onboarding__head">
			<span class="match-jb-onboarding__badge" aria-hidden="true">
				<span><?php echo match_icon( 'hand-wave' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</span>
			<div class="match-jb-onboarding__text">
				<h2 id="match-jb-onboarding-title" class="match-jb-onboarding__title"><?php echo wp_kses( __( 'Todo listo para<br>empezar.', 'match' ), array( 'br' => array() ) ); ?></h2>
				<p class="match-jb-onboarding__lead"><?php esc_html_e( 'Unas cosas rápidas antes de explorar las vacantes.', 'match' ); ?></p>
			</div>
		</div>

		<div class="match-jb-onboarding__body">
			<ol class="match-jb-onboarding__steps">
				<?php foreach ( $steps as $i => list( $title, $desc ) ) : ?>
					<li class="match-jb-onboarding__step">
						<span class="match-jb-onboarding__num" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
						<div>
							<p class="match-jb-onboarding__step-title"><?php echo esc_html( $title ); ?></p>
							<p class="match-jb-onboarding__step-desc"><?php echo esc_html( $desc ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>

			<hr class="match-jb-onboarding__rule">

			<div class="match-jb-onboarding__footer">
				<a class="match-jb__btn match-jb__btn--light" href="<?php echo esc_url( match_jobs_url() ); ?>" data-onboarding-done><?php esc_html_e( 'Explorar vacantes', 'match' ); ?></a>
				<a class="match-jb__btn match-jb__btn--dark" href="<?php echo esc_url( match_profile_url() ); ?>" data-onboarding-done><?php esc_html_e( 'Completar perfil', 'match' ); ?></a>
			</div>
		</div>
	</div>
</dialog>
