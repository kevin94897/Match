<?php
/**
 * Barra superior del panel (Figma: Topbar-JobBoard, node 4365:4286; con
 * "Volver a Vacantes" en el detalle, node 4059:6063).
 *
 * Con sesión agrega, a la derecha, notificaciones y un menú de cuenta
 * (avatar + nombre) con accesos a Perfil y Cerrar sesión: en móvil la
 * barra lateral pasa a una fila con scroll horizontal y esas dos acciones
 * quedan fuera de vista, así que la topbar es su entrada siempre visible.
 *
 * @var array $args { back?: array{label:string,url:string} }
 */
defined( 'ABSPATH' ) || exit;

$back   = $args['back'] ?? null;
$logged = is_user_logged_in();
$user   = $logged ? wp_get_current_user() : null;
?>
<header class="match-jb__topbar">
	<div class="match-jb__topbar-start">
		<?php if ( $back ) : ?>
			<a class="match-jb__back" href="<?php echo esc_url( $back['url'] ); ?>">
				<?php echo match_icon( 'arrow-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php echo esc_html( $back['label'] ); ?>
			</a>
		<?php elseif ( $logged ) : ?>
			<p class="match-jb__welcome">
				<?php
				/* translators: %s: nombre */
				printf( esc_html__( 'Bienvenido, %s', 'match' ), esc_html( match_user_first_name( $user ) ) );
				?>
				<?php echo match_icon( 'hand-wave' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</p>
		<?php else : ?>
			<p><?php esc_html_e( 'Vacantes exclusivas de Match', 'match' ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( $logged ) : ?>
		<div class="match-jb__topbar-actions">
			<button class="match-jb__notif" type="button" aria-disabled="true" title="<?php esc_attr_e( 'Disponible próximamente', 'match' ); ?>">
				<?php echo match_icon( 'bell' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="match-jb__notif-label"><?php esc_html_e( 'Notificaciones', 'match' ); ?></span>
			</button>

			<span class="match-jb__topbar-sep" aria-hidden="true"></span>

			<details class="match-jb-account">
				<summary class="match-jb-account__trigger">
					<span class="match-jb-account__avatar" aria-hidden="true"><?php echo match_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="match-jb-account__who">
						<span class="match-jb-account__name"><?php echo esc_html( $user->display_name ); ?></span>
						<span class="match-jb-account__mail"><?php echo esc_html( $user->user_email ); ?></span>
					</span>
					<?php echo match_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</summary>

				<div class="match-jb-account__menu" role="menu">
					<a class="match-jb-account__item" href="<?php echo esc_url( match_profile_url() ); ?>" role="menuitem">
						<?php echo match_icon( 'user-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php esc_html_e( 'Ver perfil', 'match' ); ?>
					</a>
					<a class="match-jb-account__item is-danger" href="<?php echo esc_url( match_logout_url() ); ?>" role="menuitem">
						<?php echo match_icon( 'logout' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php esc_html_e( 'Cerrar sesión', 'match' ); ?>
					</a>
				</div>
			</details>
		</div>
	<?php endif; ?>
</header>
