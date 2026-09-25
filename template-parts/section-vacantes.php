<?php
/**
 * Vacantes activas (Figma: node 3502:3421).
 *
 * Filtra en el sitio: los selects consultan match/v1/jobs (inc/jobs-filters.php)
 * y app.js reemplaza las filas, el conteo y las opciones. Sin JavaScript el
 * formulario envía al archivo de vacantes. Mientras el CRM no sincronice
 * vacantes, se muestran las cuatro filas de ejemplo del diseño.
 */
defined( 'ABSPATH' ) || exit;

$current = match_jobs_current_filters();
$list    = match_jobs_list( $current );
$facets  = match_jobs_facets( $current );
$jobs    = $list['jobs'];

$filters = array(
	'ubicacion' => array( __( 'Ubicación', 'match' ), 'filter-location' ),
	'nivel'     => array( __( 'Nivel', 'match' ), 'filter-level' ),
	'modalidad' => array( __( 'Modalidad', 'match' ), 'filter-mode' ),
	'industria' => array( __( 'Industria', 'match' ), 'filter-industry' ),
);
?>
<section class="match-vacantes-section" id="vacantes">
	<div class="match-vacantes">
		<?php match_section_head( match_home()['v_eyebrow'], match_home()['v_title'], '', 'h2-xl', true ); ?>

		<div class="match-vacantes__body" data-aos="fade-up" data-aos-delay="100">
			<div class="match-vacantes__toolbar">
				<div class="match-vacantes__bar">
					<form class="match-vacantes__filters" method="get" action="<?php echo esc_url( match_jobs_url() ); ?>" data-jobs-form data-endpoint="<?php echo esc_url( rest_url( 'match/v1/jobs' ) ); ?>">
						<div class="match-vacantes__pills">
							<?php foreach ( $filters as $param => list( $label, $icon ) ) : ?>
								<?php
								$options  = $facets[ $param ] ?? array();
								$selected = $current[ $param ] ?? '';
								$in_list  = in_array( $selected, array_column( $options, 'slug' ), true );
								$active   = $selected ? match_jobs_term_name( $param, $selected ) : '';
								?>
								<label class="match-filter<?php echo $selected ? ' is-active' : ''; ?>">
									<?php echo match_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									<span data-filter-label><?php echo esc_html( $active ?: $label ); ?></span>
									<select name="<?php echo esc_attr( $param ); ?>" data-label="<?php echo esc_attr( $label ); ?>" onchange="this.form.submit()" aria-label="<?php echo esc_attr( $label ); ?>">
										<option value=""><?php echo esc_html( $label ); ?></option>
										<?php foreach ( $options as $option ) : ?>
											<option value="<?php echo esc_attr( $option['slug'] ); ?>" data-name="<?php echo esc_attr( $option['name'] ); ?>"<?php selected( $selected, $option['slug'] ); ?>><?php echo esc_html( $option['name'] ); ?> (<?php echo (int) $option['count']; ?>)</option>
										<?php endforeach; ?>
										<?php if ( $selected && ! $in_list ) : ?>
											<option value="<?php echo esc_attr( $selected ); ?>" data-name="<?php echo esc_attr( $active ); ?>" selected><?php echo esc_html( $active ); ?> (0)</option>
										<?php endif; ?>
									</select>
								</label>
							<?php endforeach; ?>
						</div>

						<span class="match-vacantes__divider" aria-hidden="true" data-jobs-divider<?php echo $current ? '' : ' hidden'; ?>></span>

						<button class="match-vacantes__clear" type="reset" data-jobs-clear<?php echo $current ? '' : ' hidden'; ?>>
							<?php echo match_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php esc_html_e( 'Borrar filtros', 'match' ); ?>
						</button>
						<button class="screen-reader-text" type="submit"><?php esc_html_e( 'Aplicar filtros', 'match' ); ?></button>
					</form>

					<a class="match-vacantes__all" href="<?php echo esc_url( match_jobs_archive_url( $current ) ); ?>" data-jobs-all>
						<?php esc_html_e( 'Ver todas las vacantes', 'match' ); ?>
						<?php echo match_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>

				<p class="match-vacantes__count" data-jobs-count aria-live="polite">
					<?php echo match_jobs_count_html( count( $jobs ), $list['total'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</p>
			</div>

			<div class="match-vacantes__list" data-jobs-list aria-busy="false">
				<?php echo match_jobs_render_rows( $jobs ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
	</div>
</section>
