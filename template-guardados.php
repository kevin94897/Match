<?php
/**
 * Template Name: Job Board — Guardados
 *
 * Vacantes guardadas (Figma: Job-Board/Guardados, node 4282:3792). Exige
 * sesión. Filtro Todas / Sin postular / Postuladas por GET (?estado=).
 * Guardar y quitar es en vivo desde el marcador de cada tarjeta.
 */
defined( 'ABSPATH' ) || exit;

$estado  = isset( $_GET['estado'] ) && in_array( $_GET['estado'], array( 'sin-postular', 'postuladas' ), true ) ? $_GET['estado'] : 'todas'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$applied = match_jobboard_applied_keys();
$all     = match_jobboard_saved_jobs();
$jobs    = array_values(
	array_filter(
		$all,
		function ( array $job ) use ( $estado, $applied ): bool {
			$is_applied = in_array( (string) $job['id'], $applied, true );
			return 'todas' === $estado || ( 'postuladas' === $estado ? $is_applied : ! $is_applied );
		}
	)
);
$tabs    = array(
	'todas'        => __( 'Todas', 'match' ),
	'sin-postular' => __( 'Sin postular', 'match' ),
	'postuladas'   => __( 'Postuladas', 'match' ),
);
$base    = (string) get_permalink();

get_header( 'jobboard' );
?>
<div class="match-jb">
	<?php get_template_part( 'template-parts/jobboard/sidebar', null, array( 'current' => 'guardados' ) ); ?>

	<main id="main" class="match-jb__content">
		<?php get_template_part( 'template-parts/jobboard/topbar' ); ?>

		<div class="match-jb__body match-jb__body--saved">
			<header class="match-jb-saved__head" data-aos="fade-up">
				<div class="match-jb-saved__title">
					<h1><?php esc_html_e( 'Guardados', 'match' ); ?></h1>
					<span class="match-jb-saved__count" data-saved-count>
						<?php
						/* translators: %s: número */
						echo esc_html( sprintf( _n( '%s vacante', '%s vacantes', count( $all ), 'match' ), number_format_i18n( count( $all ) ) ) );
						?>
					</span>
				</div>
				<nav class="match-jb-chips" aria-label="<?php esc_attr_e( 'Filtrar guardados', 'match' ); ?>">
					<?php foreach ( $tabs as $key => $label ) : ?>
						<a class="match-jb-chip<?php echo $key === $estado ? ' is-active' : ''; ?>" href="<?php echo esc_url( 'todas' === $key ? $base : add_query_arg( 'estado', $key, $base ) ); ?>"<?php echo $key === $estado ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
					<?php endforeach; ?>
				</nav>
			</header>

			<div class="match-jb__grid match-jb__grid--two" data-saved-grid data-aos="fade-up" data-aos-delay="80"<?php echo $jobs ? '' : ' hidden'; ?>>
				<?php foreach ( $jobs as $job ) : ?>
					<?php
					get_template_part(
						'template-parts/jobboard/job-card',
						null,
						array(
							'job'       => $job,
							'status'    => in_array( (string) $job['id'], $applied, true ) ? __( 'Postulado', 'match' ) : '',
							/* translators: %s: fecha */
							'date_text' => $job['saved_at'] ? sprintf( __( 'Guardado el %s', 'match' ), $job['saved_at'] ) : '',
						)
					);
					?>
				<?php endforeach; ?>
			</div>

			<div class="match-jb-empty match-jb-empty--page" data-saved-empty<?php echo $jobs ? ' hidden' : ''; ?>>
				<span class="match-jb-empty__icon" aria-hidden="true"><?php echo match_icon( 'bookmark' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<p class="match-jb-empty__title">
					<?php echo 'todas' === $estado ? esc_html__( 'Aún no tienes vacantes guardadas', 'match' ) : esc_html__( 'No hay vacantes en este filtro', 'match' ); ?>
				</p>
				<p class="match-jb-empty__text"><?php esc_html_e( 'Guarda las que te interesen con el marcador de cada tarjeta para volver a ellas más tarde.', 'match' ); ?></p>
				<a class="match-jb__btn match-jb__btn--dark" href="<?php echo esc_url( match_jobs_url() ); ?>"><?php esc_html_e( 'Ver vacantes', 'match' ); ?></a>
			</div>
		</div>
	</main>
</div>
<?php
get_footer( 'jobboard' );
