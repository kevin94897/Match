<?php
/**
 * Detalle de vacante.
 *
 * "Requisitos" no llega como campo aparte: el CRM lo incluye dentro de
 * description, así que se pinta como un único bloque de contenido.
 */
defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$job_id   = get_the_ID();
	$company  = match_job_board_active() ? (string) MJB_Helpers::get_meta( $job_id, 'company_name' ) : '';
	$benefits = match_job_board_active() ? (string) MJB_Helpers::get_meta( $job_id, 'benefits' ) : '';
	$skills   = match_job_board_active() ? (array) MJB_Helpers::get_meta( $job_id, 'skills', array() ) : array();
	$deadline = match_job_board_active() ? (string) MJB_Helpers::get_meta( $job_id, 'deadline' ) : '';
	$closed   = match_job_board_active() && 'published' !== MJB_Helpers::get_meta( $job_id, 'status', 'published' );
	?>

	<article class="match-job">
		<header class="match-job__header">
			<div class="match-container match-job__header-inner">
				<a class="match-job__back" href="<?php echo esc_url( match_jobs_url() ); ?>">
					<?php esc_html_e( 'Volver a vacantes', 'match' ); ?>
				</a>

				<div class="match-job__identity">
					<?php match_company_logo( $job_id, 72 ); ?>

					<div>
						<h1 class="match-job__title"><?php the_title(); ?></h1>
						<p class="match-job__company"><?php echo esc_html( $company ); ?></p>

						<ul class="match-job__chips">
							<?php foreach ( match_job_meta( $job_id ) as $chip ) : ?>
								<li><?php echo esc_html( $chip ); ?></li>
							<?php endforeach; ?>
							<li><?php echo esc_html( match_job_salary( $job_id ) ); ?></li>
						</ul>
					</div>
				</div>

				<div class="match-job__cta">
					<?php if ( $closed ) : ?>
						<p class="match-job__closed"><?php esc_html_e( 'Este proceso ya cerró.', 'match' ); ?></p>
					<?php else : ?>
						<a class="match-btn match-btn--primary" href="#postular"><?php esc_html_e( 'Postular ahora', 'match' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</header>

		<div class="match-container match-job__body">
			<div class="match-job__main">
				<section class="match-job__block">
					<h2 class="match-job__block-title"><?php esc_html_e( 'Descripción del puesto', 'match' ); ?></h2>
					<div class="match-job__prose"><?php the_content(); ?></div>
				</section>

				<?php if ( $benefits ) : ?>
					<section class="match-job__block">
						<h2 class="match-job__block-title"><?php esc_html_e( 'Lo que ofrecemos', 'match' ); ?></h2>
						<div class="match-job__prose"><?php echo wp_kses_post( wpautop( $benefits ) ); ?></div>
					</section>
				<?php endif; ?>
			</div>

			<aside class="match-job__aside">
				<section class="match-job__panel">
					<h2 class="match-job__panel-title"><?php esc_html_e( 'Resumen del rol', 'match' ); ?></h2>
					<dl class="match-job__summary">
						<?php
						$rows = array(
							__( 'Nivel', 'match' )      => MJB_Helpers::get_meta( $job_id, 'seniority' ),
							__( 'Modalidad', 'match' )  => MJB_Helpers::work_mode_label( (string) MJB_Helpers::get_meta( $job_id, 'work_mode' ) ),
							__( 'Jornada', 'match' )    => MJB_Helpers::employment_type_label( (string) MJB_Helpers::get_meta( $job_id, 'employment_type' ) ),
							__( 'Ubicación', 'match' )  => trim( implode( ', ', array_filter( array( MJB_Helpers::get_meta( $job_id, 'city' ), MJB_Helpers::get_meta( $job_id, 'country' ) ) ) ), ', ' ),
							__( 'Salario', 'match' )    => match_job_salary( $job_id ),
							__( 'Publicado', 'match' )  => get_the_date( 'j M, Y' ),
							__( 'Postula hasta', 'match' ) => $deadline ? date_i18n( 'j M, Y', strtotime( $deadline ) ) : '',
						);

						foreach ( $rows as $label => $value ) :
							if ( '' === trim( (string) $value ) ) {
								continue;
							}
							?>
							<div class="match-job__summary-row">
								<dt><?php echo esc_html( $label ); ?></dt>
								<dd><?php echo esc_html( $value ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>
				</section>

				<?php
				/*
				 * Skills: el CRM dejó de capturarlas, así que este bloque solo
				 * aparece en las vacantes cargadas antes de ese cambio. La
				 * columna no deja hueco cuando falta.
				 */
				if ( $skills ) :
					?>
					<section class="match-job__panel">
						<h2 class="match-job__panel-title"><?php esc_html_e( 'Habilidades requeridas', 'match' ); ?></h2>
						<ul class="mjb-tags">
							<?php foreach ( $skills as $skill ) : ?>
								<li class="mjb-tag"><?php echo esc_html( $skill ); ?></li>
							<?php endforeach; ?>
						</ul>
					</section>
				<?php endif; ?>
			</aside>
		</div>

		<?php if ( ! $closed ) : ?>
			<div class="match-container match-job__apply" id="postular">
				<?php echo do_shortcode( '[match_apply_form job="' . (int) $job_id . '"]' ); ?>
			</div>
		<?php endif; ?>
	</article>

<?php
endwhile;

get_footer();
