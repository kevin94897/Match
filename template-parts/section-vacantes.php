<?php
/**
 * Vacantes activas (Figma: node 3502:3421).
 *
 * Pinta las más recientes del Job Board. Mientras el CRM no sincronice
 * vacantes, se muestran las cuatro filas de ejemplo del diseño para que la
 * maqueta se pueda revisar completa.
 */
defined( 'ABSPATH' ) || exit;

$jobs = match_home_jobs( 4 );

$filters = array(
	'ubicacion' => array( __( 'Ubicación', 'match' ), 'filter-location' ),
	'nivel'     => array( __( 'Nivel', 'match' ), 'filter-level' ),
	'modalidad' => array( __( 'Modalidad', 'match' ), 'filter-mode' ),
	'industria' => array( __( 'Industria', 'match' ), 'filter-industry' ),
);
?>
<section class="match-vacantes-section" id="vacantes">
	<div class="match-vacantes">
		<?php
		match_section_head(
			__( 'Vacantes activas', 'match' ),
			__( 'Vacantes.', 'match' ),
			'',
			'h2-xl',
			true
		);
		?>

		<div class="match-vacantes__body">
			<div class="match-vacantes__toolbar">
				<div class="match-vacantes__bar">
					<form class="match-vacantes__filters" method="get" action="<?php echo esc_url( match_jobs_url() ); ?>">
						<div class="match-vacantes__pills">
							<?php foreach ( $filters as $param => list( $label, $icon ) ) : ?>
								<?php $options = match_job_board_active() ? MJB_Query::filter_options( $param ) : array(); ?>
								<label class="match-filter">
									<?php echo match_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									<span><?php echo esc_html( $label ); ?></span>
									<select name="<?php echo esc_attr( $param ); ?>" onchange="this.form.submit()" aria-label="<?php echo esc_attr( $label ); ?>">
										<option value=""><?php echo esc_html( $label ); ?></option>
										<?php foreach ( $options as $term ) : ?>
											<option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?> (<?php echo (int) $term->count; ?>)</option>
										<?php endforeach; ?>
									</select>
								</label>
							<?php endforeach; ?>
						</div>

						<span class="match-vacantes__divider" aria-hidden="true"></span>

						<button class="match-vacantes__clear" type="reset">
							<?php echo match_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php esc_html_e( 'Borrar filtros', 'match' ); ?>
						</button>
					</form>

					<a class="match-vacantes__all" href="<?php echo esc_url( match_jobs_url() ); ?>">
						<?php esc_html_e( 'Ver todas las vacantes', 'match' ); ?>
						<?php echo match_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>

				<p class="match-vacantes__count">
					<span class="is-dim"><?php esc_html_e( 'Mostrando', 'match' ); ?></span>
					<?php echo esc_html( number_format_i18n( count( $jobs ) ) ); ?>
					<span class="is-muted"><?php echo esc_html( _n( 'vacante', 'vacantes', count( $jobs ), 'match' ) ); ?></span>
				</p>
			</div>

			<div class="match-vacantes__list">
				<?php foreach ( $jobs as $index => $job ) : ?>
					<?php get_template_part( 'template-parts/job', 'row', array( 'job' => $job, 'open' => 0 === $index ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
