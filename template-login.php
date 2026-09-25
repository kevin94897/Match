<?php
/**
 * Template Name: Job Board — Login
 *
 * Inicio de sesión (Figma: Job-Board/Login — Glassy, node 4308:3581).
 * Envía a wp-login.php; inc/jobboard.php trae de vuelta los errores y
 * redirige al panel al entrar.
 */
defined( 'ABSPATH' ) || exit;

$notice      = match_login_notice();
$redirect_to = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : match_jobboard_url(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$google_url  = match_google_login_url();

get_header( 'jobboard' );
?>
<main id="main" class="match-login-page">
	<div class="match-login-page__bg" aria-hidden="true">
		<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/jobboard/login-bg.webp' ) ); ?>" alt="" width="1920" height="1280" fetchpriority="high" decoding="async">
	</div>

	<div class="match-login-page__inner">
		<div class="match-login-page__intro" data-aos="fade-up">
			<h1 class="match-login-page__title"><?php esc_html_e( 'Inicia sesión', 'match' ); ?></h1>
			<p class="match-login-page__lead"><?php esc_html_e( 'Vacantes exclusivas en las empresas más reconocidas del país, gestionadas por Match.', 'match' ); ?></p>
		</div>

		<form class="match-glass match-login-card" method="post" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" data-aos="fade-up" data-aos-delay="120">
			<a class="match-login-card__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php echo match_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>

			<a class="match-login-card__google<?php echo $google_url ? '' : ' is-disabled'; ?>" href="<?php echo esc_url( $google_url ?: '#' ); ?>"<?php echo $google_url ? '' : ' aria-disabled="true" title="' . esc_attr__( 'Disponible próximamente', 'match' ) . '"'; ?>>
				<span class="match-login-card__google-icon" aria-hidden="true"><?php echo match_icon( 'google' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<?php esc_html_e( 'Continuar con Google', 'match' ); ?>
			</a>

			<p class="match-login-card__or" aria-hidden="true"><span><?php esc_html_e( 'o continuar con Email', 'match' ); ?></span></p>

			<?php if ( $notice ) : ?>
				<p class="match-login-card__notice match-login-card__notice--<?php echo esc_attr( $notice['type'] ); ?>" role="<?php echo 'error' === $notice['type'] ? 'alert' : 'status'; ?>"><?php echo esc_html( $notice['text'] ); ?></p>
			<?php endif; ?>

			<div class="match-login-card__fields">
				<label class="match-field">
					<span class="match-field__label"><?php esc_html_e( 'Correo electrónico', 'match' ); ?></span>
					<input class="match-field__input" type="text" name="log" placeholder="tu@correo.com" required autocomplete="username" autocapitalize="off" spellcheck="false">
				</label>
				<div class="match-login-card__password">
					<label class="match-field">
						<span class="match-field__label"><?php esc_html_e( 'Contraseña', 'match' ); ?></span>
						<input class="match-field__input" type="password" name="pwd" placeholder="••••••••••••" required autocomplete="current-password">
					</label>
					<a class="match-login-card__forgot" href="<?php echo esc_url( wp_lostpassword_url( match_login_page_url() ) ); ?>"><?php esc_html_e( '¿Olvidaste tu contraseña?', 'match' ); ?></a>
				</div>
			</div>

			<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect_to ); ?>">
			<input type="hidden" name="testcookie" value="1">
			<input type="hidden" name="rememberme" value="forever">

			<div class="match-login-card__submit">
				<button class="match-btn match-btn--primary" type="submit">
					<span class="match-btn__orbit" aria-hidden="true"></span>
					<?php esc_html_e( 'Ingresar', 'match' ); ?>
					<span class="match-btn__icon"><?php echo match_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</button>
				<p class="match-login-card__register">
					<?php esc_html_e( '¿No tienes cuenta?', 'match' ); ?>
					<a href="<?php echo esc_url( match_registro_page_url() ?: home_url( '/#contacto' ) ); ?>"><?php esc_html_e( 'Regístrate ahora', 'match' ); ?></a>
				</p>
			</div>
		</form>
	</div>

	<footer class="match-login-page__legal">
		<p><?php printf( esc_html__( '© %s Match. Todos los derechos reservados.', 'match' ), esc_html( wp_date( 'Y' ) ) ); ?></p>
		<nav aria-label="<?php esc_attr_e( 'Legal', 'match' ); ?>">
			<a href="<?php echo esc_url( home_url( '/terminos-de-servicio/' ) ); ?>"><?php esc_html_e( 'Términos y condiciones', 'match' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/politica-de-privacidad/' ) ); ?>"><?php esc_html_e( 'Política de privacidad', 'match' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/#contacto' ) ); ?>"><?php esc_html_e( 'Contacto', 'match' ); ?></a>
		</nav>
	</footer>
</main>
<?php
get_footer( 'jobboard' );
