<?php
/**
 * Página no encontrada.
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="match-container">
	<div class="mjb-empty">
		<p class="mjb-empty__title"><?php esc_html_e( 'Esta página ya no está aquí', 'match' ); ?></p>
		<p class="mjb-empty__text"><?php esc_html_e( 'Puede que la vacante haya cerrado o que el enlace esté mal escrito.', 'match' ); ?></p>
		<p>
			<a class="match-btn match-btn--primary" href="<?php echo esc_url( match_jobs_url() ); ?>">
				<?php esc_html_e( 'Ver vacantes', 'match' ); ?>
			</a>
			<a class="match-btn match-btn--secondary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Ir al inicio', 'match' ); ?>
			</a>
		</p>
	</div>
</div>
<?php
get_footer();
