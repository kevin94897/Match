<?php
/**
 * Template Name: Job Board — Perfil
 *
 * Perfil del candidato (Figma: Job-Board/Perfil, node 4216:3). Exige sesión.
 * Tres formularios independientes: datos de cuenta, CV y enlace de cambio
 * de contraseña; los procesa inc/jobboard.php vía admin-post.php.
 */
defined( 'ABSPATH' ) || exit;

$user   = wp_get_current_user();
$cv     = match_user_cv();
$notice = match_profile_notice();

get_header( 'jobboard' );
?>
<div class="match-jb">
	<?php get_template_part( 'template-parts/jobboard/sidebar', null, array( 'current' => 'perfil' ) ); ?>

	<main id="main" class="match-jb__content">
		<?php get_template_part( 'template-parts/jobboard/topbar' ); ?>

		<div class="match-jb__body match-jb__body--center">
			<div class="match-jb-profile" data-aos="fade-up">
				<?php if ( $notice ) : ?>
					<p class="match-jb-notice match-jb-notice--<?php echo esc_attr( $notice['type'] ); ?>" role="<?php echo 'error' === $notice['type'] ? 'alert' : 'status'; ?>"><?php echo esc_html( $notice['text'] ); ?></p>
				<?php endif; ?>

				<div class="match-jb-avatar">
					<span class="match-jb-avatar__img" aria-hidden="true"><?php echo match_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<div class="match-jb-avatar__text">
						<p class="match-jb-avatar__name"><?php echo esc_html( $user->display_name ); ?></p>
						<p class="match-jb-avatar__mail"><?php echo esc_html( $user->user_email ); ?></p>
					</div>
				</div>

				<div class="match-jb-profile__card">
					<form class="match-jb-profile__block" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<h2 class="match-jb-profile__title"><?php esc_html_e( 'Información de cuenta', 'match' ); ?></h2>
						<input type="hidden" name="action" value="match_profile_save">
						<?php wp_nonce_field( 'match_profile_save', '_match_nonce' ); ?>
						<div class="match-jb-profile__fields">
							<label class="match-jb-field">
								<span class="screen-reader-text"><?php esc_html_e( 'Correo electrónico', 'match' ); ?></span>
								<input class="match-jb-field__input" type="email" name="email" value="<?php echo esc_attr( $user->user_email ); ?>" placeholder="<?php esc_attr_e( 'Correo electrónico', 'match' ); ?>" required autocomplete="email">
							</label>
							<div class="match-jb-profile__row">
								<label class="match-jb-field">
									<span class="screen-reader-text"><?php esc_html_e( 'Nombre', 'match' ); ?></span>
									<input class="match-jb-field__input" type="text" name="first_name" value="<?php echo esc_attr( $user->first_name ); ?>" placeholder="<?php esc_attr_e( 'Nombre', 'match' ); ?>" autocomplete="given-name">
								</label>
								<label class="match-jb-field">
									<span class="screen-reader-text"><?php esc_html_e( 'Apellido', 'match' ); ?></span>
									<input class="match-jb-field__input" type="text" name="last_name" value="<?php echo esc_attr( $user->last_name ); ?>" placeholder="<?php esc_attr_e( 'Apellido', 'match' ); ?>" autocomplete="family-name">
								</label>
							</div>
							<button class="match-jb__btn match-jb__btn--dark match-jb__btn--block" type="submit"><?php esc_html_e( 'Guardar cambios', 'match' ); ?></button>
						</div>
					</form>

					<hr class="match-jb-profile__rule">

					<form class="match-jb-profile__block" id="cv" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
						<h2 class="match-jb-profile__title"><?php esc_html_e( 'Mi CV', 'match' ); ?></h2>
						<input type="hidden" name="action" value="match_profile_cv">
						<?php wp_nonce_field( 'match_profile_cv', '_match_nonce' ); ?>
						<div class="match-jb-profile__fields">
							<?php get_template_part( 'template-parts/jobboard/cv-row', null, array( 'cv' => $cv ) ); ?>
							<label class="match-jb__btn match-jb__btn--dark match-jb__btn--block">
								<?php echo $cv ? esc_html__( 'Reemplazar CV', 'match' ) : esc_html__( 'Subir CV', 'match' ); ?>
								<input class="screen-reader-text" type="file" name="cv" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required data-autosubmit>
							</label>
							<noscript><button class="match-jb__btn match-jb__btn--light match-jb__btn--block" type="submit"><?php esc_html_e( 'Guardar CV', 'match' ); ?></button></noscript>
						</div>
					</form>

					<hr class="match-jb-profile__rule">

					<form class="match-jb-profile__block" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<div class="match-jb-profile__head">
							<h2 class="match-jb-profile__title"><?php esc_html_e( 'Cambiar contraseña', 'match' ); ?></h2>
							<p class="match-jb-profile__help">
								<?php
								/* translators: %s: correo */
								printf( esc_html__( 'Te enviamos el link a %s para que puedas crear una nueva contraseña.', 'match' ), esc_html( $user->user_email ) );
								?>
							</p>
						</div>
						<input type="hidden" name="action" value="match_profile_reset">
						<?php wp_nonce_field( 'match_profile_reset', '_match_nonce' ); ?>
						<button class="match-jb__btn match-jb__btn--dark match-jb__btn--block" type="submit"><?php esc_html_e( 'Enviar link de reseteo', 'match' ); ?></button>
					</form>
				</div>
			</div>
		</div>
	</main>
</div>
<?php
get_footer( 'jobboard' );
