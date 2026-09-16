<?php
/**
 * Listado de vacantes.
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="match-section match-section--vacantes match-section--archive">
	<div class="match-container">
		<div class="match-vacantes">
			<header class="match-section__head match-section__head--dark">
				<p class="match-eyebrow">
					<span class="match-eyebrow__mark" aria-hidden="true"></span>
					<?php esc_html_e( 'Vacantes activas', 'match' ); ?>
				</p>
				<h1 class="match-h2-xl"><?php esc_html_e( 'Vacantes.', 'match' ); ?></h1>
			</header>

			<div class="mjb-jobs mjb-jobs--dark">
				<?php
				if ( match_job_board_active() ) {
					echo MJB_Shortcodes::template( 'jobs-filters.php' ); // phpcs:ignore
				}
				?>

				<p class="mjb-jobs__count">
					<?php
					global $wp_query;
					printf(
						esc_html( _n( 'Mostrando %s vacante', 'Mostrando %s vacantes', (int) $wp_query->found_posts, 'match' ) ),
						'<strong>' . esc_html( number_format_i18n( (int) $wp_query->found_posts ) ) . '</strong>'
					);
					?>
				</p>

				<?php if ( have_posts() ) : ?>
					<div class="mjb-jobs__list">
						<?php
						while ( have_posts() ) :
							the_post();
							echo MJB_Shortcodes::template( 'job-card.php', array( 'job_id' => get_the_ID() ) ); // phpcs:ignore
						endwhile;
						?>
					</div>

					<nav class="mjb-pager" aria-label="<?php esc_attr_e( 'Paginación de vacantes', 'match' ); ?>">
						<?php
						the_posts_pagination(
							array(
								'prev_text' => __( 'Anterior', 'match' ),
								'next_text' => __( 'Siguiente', 'match' ),
								'mid_size'  => 1,
							)
						);
						?>
					</nav>
				<?php else : ?>
					<div class="mjb-empty">
						<p class="mjb-empty__title"><?php esc_html_e( 'No hay vacantes que coincidan', 'match' ); ?></p>
						<p class="mjb-empty__text"><?php esc_html_e( 'Prueba quitando algún filtro para ver más resultados.', 'match' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
