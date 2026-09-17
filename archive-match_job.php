<?php
/**
 * Listado de vacantes (Figma: Job-Board/Entrance, node 4009:3081).
 *
 * Marco del Job Board (barra lateral + topbar). Búsqueda, cuatro filtros
 * con conteo facetado, orden y paginación por GET: los selects envían el
 * formulario al cambiar. Los datos salen de match_jobs_archive_query()
 * (modo demo mientras no haya vacantes sincronizadas).
 */
defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtros públicos de lectura.
$current = match_jobs_current_filters();
$q       = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
$order   = isset( $_GET['orden'] ) && 'antiguo' === $_GET['orden'] ? 'antiguo' : 'reciente';
// phpcs:enable
$page    = max( 1, (int) get_query_var( 'paged' ) );
$result  = match_jobs_archive_query( $current, $q, $order, $page, 10 );
$facets  = match_jobs_facets( $current );
$labels  = array(
	'ubicacion' => array( __( 'Ubicación', 'match' ), 'filter-location' ),
	'nivel'     => array( __( 'Nivel', 'match' ), 'filter-level' ),
	'modalidad' => array( __( 'Modalidad', 'match' ), 'filter-mode' ),
	'industria' => array( __( 'Industria', 'match' ), 'filter-industry' ),
);
$sorts   = array( 'reciente' => __( 'Más reciente', 'match' ), 'antiguo' => __( 'Más antiguo', 'match' ) );
$base    = match_jobs_url();

get_header( 'jobboard' );
?>
<div class="match-jb">
	<?php get_template_part( 'template-parts/jobboard/sidebar', null, array( 'current' => 'vacantes' ) ); ?>

	<main id="main" class="match-jb__content">
		<?php get_template_part( 'template-parts/jobboard/topbar' ); ?>

		<div class="match-jb__body match-jb__body--archive">
			<form class="match-jb-filters" method="get" action="<?php echo esc_url( $base ); ?>" data-aos="fade-up">
				<label class="match-jb-search">
					<?php echo match_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Buscar', 'match' ); ?></span>
					<input type="search" name="q" value="<?php echo esc_attr( $q ); ?>" placeholder="<?php esc_attr_e( 'Buscar entre las vacantes...', 'match' ); ?>" autocomplete="off">
				</label>

				<div class="match-jb-filters__pills">
					<?php foreach ( $labels as $param => list( $label, $icon ) ) : ?>
						<?php
						$options  = $facets[ $param ] ?? array();
						$selected = $current[ $param ] ?? '';
						$in_list  = in_array( $selected, array_column( $options, 'slug' ), true );
						$active   = $selected ? match_jobs_term_name( $param, $selected ) : '';
						?>
						<label class="match-jb-filter<?php echo $selected ? ' is-active' : ''; ?>">
							<?php echo match_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span><?php echo esc_html( $active ?: $label ); ?></span>
							<select name="<?php echo esc_attr( $param ); ?>" onchange="this.form.submit()" aria-label="<?php echo esc_attr( $label ); ?>">
								<option value=""><?php echo esc_html( $label ); ?></option>
								<?php foreach ( $options as $option ) : ?>
									<option value="<?php echo esc_attr( $option['slug'] ); ?>"<?php selected( $selected, $option['slug'] ); ?>><?php echo esc_html( $option['name'] ); ?> (<?php echo (int) $option['count']; ?>)</option>
								<?php endforeach; ?>
								<?php if ( $selected && ! $in_list ) : ?>
									<option value="<?php echo esc_attr( $selected ); ?>" selected><?php echo esc_html( $active ); ?> (0)</option>
								<?php endif; ?>
							</select>
						</label>
					<?php endforeach; ?>

					<?php if ( $current || $q ) : ?>
						<a class="match-jb-filters__clear" href="<?php echo esc_url( $base ); ?>">
							<?php echo match_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php esc_html_e( 'Borrar filtros', 'match' ); ?>
						</a>
					<?php endif; ?>
				</div>
				<input type="hidden" name="orden" value="<?php echo esc_attr( $order ); ?>">
				<button class="screen-reader-text" type="submit"><?php esc_html_e( 'Buscar', 'match' ); ?></button>
			</form>

			<hr class="match-jb__rule">

			<section class="match-jb-list-wrap" data-aos="fade-up" data-aos-delay="80">
				<header class="match-jb-list__head">
					<h1 class="match-jb-list__title match-jb-list__title--sm">
						<?php esc_html_e( 'Lista de empleos', 'match' ); ?>
						<span class="match-jb-list__count"><?php echo esc_html( number_format_i18n( $result['total'] ) ); ?></span>
					</h1>
					<form class="match-jb-sort" method="get" action="<?php echo esc_url( $base ); ?>">
						<?php foreach ( array_merge( $current, array_filter( array( 'q' => $q ) ) ) as $key => $value ) : ?>
							<input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>">
						<?php endforeach; ?>
						<label class="match-jb-sort__label">
							<span><?php esc_html_e( 'Ordenar por:', 'match' ); ?></span>
							<strong><?php echo esc_html( $sorts[ $order ] ); ?></strong>
							<?php echo match_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<select name="orden" onchange="this.form.submit()" aria-label="<?php esc_attr_e( 'Ordenar por', 'match' ); ?>">
								<?php foreach ( $sorts as $value => $label ) : ?>
									<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $order, $value ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</form>
				</header>

				<?php if ( $result['jobs'] ) : ?>
					<div class="match-jb-list">
						<?php foreach ( $result['jobs'] as $job ) : ?>
							<?php get_template_part( 'template-parts/jobboard/job-row', null, array( 'job' => $job ) ); ?>
						<?php endforeach; ?>

						<?php if ( $result['pages'] > 1 ) : ?>
							<nav class="match-jb-pager" aria-label="<?php esc_attr_e( 'Paginación de vacantes', 'match' ); ?>">
								<?php
								echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput
									array(
										'base'      => add_query_arg( array_merge( $current, array_filter( array( 'q' => $q, 'orden' => 'antiguo' === $order ? 'antiguo' : null ) ) ), trailingslashit( $base ) . 'page/%#%/' ),
										'format'    => '',
										'current'   => $result['page'],
										'total'     => $result['pages'],
										'prev_text' => __( 'Anterior', 'match' ),
										'next_text' => __( 'Siguiente', 'match' ),
										'mid_size'  => 1,
									)
								);
								?>
							</nav>
						<?php endif; ?>

						<?php if ( $result['page'] >= $result['pages'] ) : ?>
							<p class="match-jb-list__end"><?php esc_html_e( 'Llegaste al final de esta lista', 'match' ); ?></p>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="match-jb-empty match-jb-empty--page">
						<span class="match-jb-empty__icon" aria-hidden="true"><?php echo match_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<p class="match-jb-empty__title"><?php esc_html_e( 'No hay vacantes que coincidan', 'match' ); ?></p>
						<p class="match-jb-empty__text"><?php esc_html_e( 'Prueba con otra búsqueda o quita algún filtro para ver más resultados.', 'match' ); ?></p>
						<a class="match-jb__btn match-jb__btn--dark" href="<?php echo esc_url( $base ); ?>"><?php esc_html_e( 'Borrar filtros', 'match' ); ?></a>
					</div>
				<?php endif; ?>
			</section>
		</div>
	</main>
</div>
<?php
get_footer( 'jobboard' );
