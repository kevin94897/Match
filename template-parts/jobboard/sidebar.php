<?php
/**
 * Barra lateral del Job Board (Figma: público 4529:4, con sesión 4125:26).
 *
 * @var array $args { current: inicio|vacantes|procesos|guardados|perfil }
 */
defined( 'ABSPATH' ) || exit;

$current = $args['current'] ?? '';
$items   = array(
	'inicio'   => array( __( 'Inicio', 'match' ), match_jobboard_url(), 'home' ),
	'vacantes' => array( __( 'Vacantes', 'match' ), match_jobs_url(), 'briefcase' ),
);

$counts = array();

if ( is_user_logged_in() ) {
	// Las secciones aún sin página quedan visibles pero deshabilitadas.
	$items['procesos']  = array( __( 'Mis procesos', 'match' ), match_jobboard_page_url( 'template-procesos.php' ), 'address-book' );
	$items['guardados'] = array( __( 'Guardados', 'match' ), match_jobboard_page_url( 'template-guardados.php' ), 'bookmark' );
	$items['perfil']    = array( __( 'Perfil', 'match' ), match_profile_url(), 'user-circle' );
	$counts             = array(
		'procesos'  => count( match_user_applications() ),
		'guardados' => count( match_saved_map() ),
	);
}
?>
<aside class="match-jb__sidebar">
	<a class="match-jb__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<?php echo match_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</a>

	<nav class="match-jb__nav" aria-label="<?php esc_attr_e( 'Job Board', 'match' ); ?>">
		<?php foreach ( $items as $key => list( $label, $url, $icon ) ) : ?>
			<?php if ( $url ) : ?>
				<a class="match-jb__navitem<?php echo $key === $current ? ' is-active' : ''; ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $key === $current ? ' aria-current="page"' : ''; ?>>
					<?php echo match_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="match-jb__navlabel"><?php echo esc_html( $label ); ?></span>
					<?php if ( ! empty( $counts[ $key ] ) ) : ?>
						<span class="match-jb__navbadge" data-nav-count="<?php echo esc_attr( $key ); ?>"><?php echo (int) $counts[ $key ]; ?></span>
					<?php endif; ?>
				</a>
			<?php else : ?>
				<span class="match-jb__navitem is-disabled" title="<?php esc_attr_e( 'Disponible próximamente', 'match' ); ?>" aria-disabled="true">
					<?php echo match_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php echo esc_html( $label ); ?>
				</span>
			<?php endif; ?>
		<?php endforeach; ?>
	</nav>

	<div class="match-jb__auth">
		<?php if ( is_user_logged_in() ) : ?>
			<a class="match-jb__logout" href="<?php echo esc_url( match_logout_url() ); ?>">
				<?php echo match_icon( 'logout' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php esc_html_e( 'Cerrar sesión', 'match' ); ?>
			</a>
		<?php else : ?>
			<a class="match-jb__btn match-jb__btn--dark" href="<?php echo esc_url( match_registro_page_url() ?: home_url( '/#contacto' ) ); ?>">
				<?php esc_html_e( 'Registrarse', 'match' ); ?>
				<?php echo match_icon( 'user-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>
			<a class="match-jb__btn match-jb__btn--light" href="<?php echo esc_url( wp_login_url( match_jobboard_url() ) ); ?>">
				<?php esc_html_e( 'Iniciar sesión', 'match' ); ?>
			</a>
		<?php endif; ?>
	</div>
</aside>
