<?php
/**
 * Detalle de vacante real (Figma: Job-Board/Detalle de vacante, node 4047:3).
 * Mismo marco del Job Board (barra lateral + topbar "Volver a Vacantes") y
 * la parte jobboard/job-detail.php, que también usa el modo demo.
 *
 * "Requisitos" no llega como campo aparte: el CRM lo incluye dentro de
 * description, así que en vacantes reales ese bloque no se pinta.
 */
defined( 'ABSPATH' ) || exit;

get_header( 'jobboard' );

while ( have_posts() ) :
	the_post();
	$job = match_job_detail_data( (string) get_the_ID() );
	?>
	<div class="match-jb">
		<?php get_template_part( 'template-parts/jobboard/sidebar', null, array( 'current' => 'vacantes' ) ); ?>

		<main id="main" class="match-jb__content">
			<?php get_template_part( 'template-parts/jobboard/topbar', null, array( 'back' => array( 'label' => __( 'Volver a Vacantes', 'match' ), 'url' => match_jobs_url() ) ) ); ?>
			<?php get_template_part( 'template-parts/jobboard/job-detail', null, array( 'job' => $job ) ); ?>
		</main>
	</div>
	<?php
endwhile;

get_footer( 'jobboard' );
