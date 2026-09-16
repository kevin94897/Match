<?php
/**
 * Barra de navegación.
 *
 * Figma: Navbar (node 3502:3320). Izquierda: logo + NavPill; derecha:
 * enlace a vacantes, separador y botón secundario de sesión.
 */
defined( 'ABSPATH' ) || exit;

$account_url   = is_user_logged_in() ? home_url( '/mi-cuenta/' ) : wp_login_url();
$account_label = is_user_logged_in() ? __( 'Mi cuenta', 'match' ) : __( 'Iniciar sesión', 'match' );
?>
<header class="match-navbar" id="match-navbar">
	<div class="match-navbar__inner">
		<div class="match-navbar__left">
			<a class="match-navbar__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<?php echo match_logo(); // phpcs:ignore WordPress.Security.EscapeOutput -- SVG propio del tema. ?>
				<?php endif; ?>
			</a>

			<nav class="match-nav" aria-label="<?php esc_attr_e( 'Navegación principal', 'match' ); ?>">
				<?php match_primary_menu( 'match-nav__list' ); ?>
			</nav>
		</div>

		<div class="match-navbar__right">
			<a class="match-nav__link" href="<?php echo esc_url( match_jobs_url() ); ?>">
				<?php esc_html_e( 'Posiciones disponibles', 'match' ); ?>
			</a>

			<span class="match-navbar__sep" aria-hidden="true"></span>

			<a class="match-btn match-btn--secondary" href="<?php echo esc_url( $account_url ); ?>">
				<?php echo esc_html( $account_label ); ?>
				<span class="match-btn__icon"><?php echo match_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</a>
		</div>

		<button class="match-navbar__toggle" type="button" aria-expanded="false" aria-controls="match-mobile-nav">
			<span class="screen-reader-text"><?php esc_html_e( 'Abrir el menú', 'match' ); ?></span>
			<span class="match-navbar__bars" aria-hidden="true"></span>
		</button>
	</div>

	<div class="match-navbar__mobile" id="match-mobile-nav" hidden>
		<?php match_primary_menu( 'match-nav__list match-nav__list--stacked' ); ?>

		<div class="match-navbar__mobile-actions">
			<a class="match-btn match-btn--primary" href="<?php echo esc_url( match_jobs_url() ); ?>">
				<?php esc_html_e( 'Posiciones disponibles', 'match' ); ?>
			</a>
			<a class="match-btn match-btn--secondary" href="<?php echo esc_url( $account_url ); ?>">
				<?php echo esc_html( $account_label ); ?>
				<span class="match-btn__icon"><?php echo match_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</a>
		</div>
	</div>
</header>
