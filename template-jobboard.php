<?php
/**
 * Template Name: Job Board
 *
 * Panel del Job Board (Figma: público 4529:3, con sesión 4125:25).
 * Barra lateral + contenido. Sin sesión: hero con caja de CV y áreas por
 * industria. Con sesión: saludo, tarjetas "Explorar" y "Mi CV activo" y
 * vacantes guardadas. Datos de inc/jobs-filters.php (modo demo sin CRM).
 */
defined( 'ABSPATH' ) || exit;

$detail   = isset( $_GET['vacante'] ) ? match_job_detail_data( sanitize_key( wp_unslash( $_GET['vacante'] ) ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$logged   = is_user_logged_in();
$featured = match_jobboard_featured( 3 );
$areas    = $logged ? array() : match_jobboard_areas( 3, 3 );
$saved    = $logged ? match_jobboard_saved_jobs( 3 ) : array();
$applied  = $logged ? match_jobboard_applied_ids() : array();
$cv       = $logged ? match_user_cv() : null;

get_header( 'jobboard' );
?>
<div class="match-jb">
	<?php get_template_part( 'template-parts/jobboard/sidebar', null, array( 'current' => $detail ? 'vacantes' : 'inicio' ) ); ?>

	<main id="main" class="match-jb__content">
		<?php if ( $detail ) : ?>
			<?php get_template_part( 'template-parts/jobboard/topbar', null, array( 'back' => array( 'label' => __( 'Volver a Vacantes', 'match' ), 'url' => match_jobs_url() ) ) ); ?>
			<?php get_template_part( 'template-parts/jobboard/job-detail', null, array( 'job' => $detail ) ); ?>
		<?php else : ?>
		<?php get_template_part( 'template-parts/jobboard/topbar' ); ?>

		<div class="match-jb__body">
			<section class="match-jb__hero" data-aos="fade-up">
				<div class="match-jb__intro">
					<h1 class="match-jb__title"><?php esc_html_e( 'Vacantes Match', 'match' ); ?></h1>
					<?php if ( $logged ) : ?>
						<p class="match-jb__subtitle"><?php esc_html_e( 'Empieza tu búsqueda', 'match' ); ?></p>
						<p class="match-jb__text"><?php esc_html_e( 'Oportunidades ejecutivas seleccionadas por nuestro equipo. Todos los procesos son gestionados por Match.', 'match' ); ?></p>
					<?php else : ?>
						<p class="match-jb__subtitle"><?php esc_html_e( 'Explora oportunidades', 'match' ); ?></p>
						<p class="match-jb__text"><?php esc_html_e( 'Oportunidades ejecutivas seleccionadas por nuestro equipo. Crea tu cuenta para postularte.', 'match' ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $logged ) : ?>
					<div class="match-jb__hero-cards">
						<div class="match-jb-box">
							<div class="match-jb-box__text">
								<p class="match-jb-box__title"><?php esc_html_e( 'Explorar vacantes', 'match' ); ?></p>
								<p class="match-jb-box__lead"><?php esc_html_e( 'Vacantes exclusivas en las empresas más reconocidas del país, seleccionadas por Match.', 'match' ); ?></p>
							</div>
							<a class="match-jb__btn match-jb__btn--light match-jb-box__btn" href="<?php echo esc_url( match_jobs_url() ); ?>"><?php esc_html_e( 'Ver todas las vacantes', 'match' ); ?></a>
						</div>
						<div class="match-jb-box">
							<div class="match-jb-box__head">
								<p class="match-jb-box__title"><?php esc_html_e( 'Mi CV activo', 'match' ); ?></p>
								<?php if ( $cv ) : ?>
									<span class="match-jb-pill match-jb-pill--ok"><?php esc_html_e( 'Activo', 'match' ); ?></span>
								<?php else : ?>
									<span class="match-jb-pill"><?php esc_html_e( 'Sin CV', 'match' ); ?></span>
								<?php endif; ?>
							</div>
							<?php get_template_part( 'template-parts/jobboard/cv-row', null, array( 'cv' => $cv ) ); ?>
							<a class="match-jb__btn match-jb__btn--light match-jb-box__btn" href="<?php echo esc_url( match_profile_url() . '#cv' ); ?>"><?php echo $cv ? esc_html__( 'Actualizar', 'match' ) : esc_html__( 'Subir CV', 'match' ); ?></a>
						</div>
					</div>
				<?php else : ?>
					<?php get_template_part( 'template-parts/jobboard/dropzone' ); ?>
				<?php endif; ?>
			</section>

			<hr class="match-jb__rule">

			<section class="match-jb__section" data-aos="fade-up">
				<header class="match-jb__section-head">
					<h2><?php esc_html_e( 'Vacantes destacadas', 'match' ); ?></h2>
					<a href="<?php echo esc_url( match_jobs_url() ); ?>"><?php esc_html_e( 'Ver todas las vacantes', 'match' ); ?></a>
				</header>
				<div class="match-jb__grid">
					<?php foreach ( $featured as $job ) : ?>
						<?php get_template_part( 'template-parts/jobboard/job-card', null, array( 'job' => $job, 'status' => in_array( (int) $job['id'], $applied, true ) ? __( 'Postulado', 'match' ) : '' ) ); ?>
					<?php endforeach; ?>
				</div>
			</section>

			<?php if ( $logged ) : ?>
				<section class="match-jb__section" id="guardados" data-aos="fade-up">
					<header class="match-jb__section-head">
						<h2><?php esc_html_e( 'Guardado', 'match' ); ?></h2>
						<?php if ( match_jobboard_page_url( 'template-guardados.php' ) ) : ?>
							<a href="<?php echo esc_url( match_jobboard_page_url( 'template-guardados.php' ) ); ?>"><?php esc_html_e( 'Ver guardados', 'match' ); ?></a>
						<?php endif; ?>
					</header>
					<?php if ( $saved ) : ?>
						<div class="match-jb__grid match-jb__grid--fixed">
							<?php foreach ( $saved as $job ) : ?>
								<?php get_template_part( 'template-parts/jobboard/job-card', null, array( 'job' => $job, 'status' => in_array( (int) $job['id'], $applied, true ) ? __( 'Postulado', 'match' ) : '' ) ); ?>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class="match-jb-empty">
							<p class="match-jb-empty__title"><?php esc_html_e( 'Aún no tienes vacantes guardadas', 'match' ); ?></p>
							<p class="match-jb-empty__text"><?php esc_html_e( 'Guarda las que te interesen con el marcador de cada tarjeta para volver a ellas más tarde.', 'match' ); ?></p>
						</div>
					<?php endif; ?>
				</section>
			<?php else : ?>
				<?php foreach ( $areas as $area ) : ?>
					<section class="match-jb__section" data-aos="fade-up">
						<header class="match-jb__section-head">
							<h2><?php echo esc_html( $area['name'] ); ?></h2>
							<a href="<?php echo esc_url( match_jobs_archive_url( array( 'industria' => $area['slug'] ) ) ); ?>"><?php esc_html_e( 'Ver todas las vacantes', 'match' ); ?></a>
						</header>
						<div class="match-jb__grid match-jb__grid--fixed">
							<?php foreach ( $area['jobs'] as $job ) : ?>
								<?php get_template_part( 'template-parts/jobboard/job-card', null, array( 'job' => $job ) ); ?>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</main>

	<?php if ( function_exists( 'match_jobboard_show_onboarding' ) && match_jobboard_show_onboarding() ) : ?>
		<?php get_template_part( 'template-parts/jobboard/onboarding' ); ?>
	<?php endif; ?>
</div>
<?php
get_footer( 'jobboard' );
