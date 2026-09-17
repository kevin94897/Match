<?php
/**
 * Detalle de vacante (Figma: Job-Board/Detalle de vacante, node 4047:3).
 * Lo usan single-match_job.php (vacantes reales) y template-jobboard.php
 * con ?vacante=demo-N (filas de ejemplo).
 *
 * @var array $args { job: array (ver match_job_detail_data) }
 */
defined( 'ABSPATH' ) || exit;

$job = $args['job'] ?? null;
if ( ! $job ) {
	return;
}
$can_apply = ! $job['closed'] && ! $job['is_demo'] && match_job_board_active();

// Si el usuario ya postuló a esta vacante, la columna de estado reemplaza al
// botón y al formulario: no tiene sentido ofrecer "Postular ahora" de nuevo
// (el plugin lo rechazaría igual, "Ya postulaste a esta vacante") ni mostrar
// el CTA. El estado por defecto de toda postulación nueva es "Enviado".
$stages      = match_application_stages();
$application = match_job_application_data( $job['id'] );
$my_stage    = $application['stage'] ?? null;
$show_cta    = ! $job['closed'] && ! $my_stage;
$stage_order = array_keys( $stages );
$current_at  = $my_stage ? array_search( $my_stage, $stage_order, true ) : false;
$cv          = $my_stage ? match_user_cv() : null;

// Vuelta del formulario de postulación (class-mjb-applications.php redirige
// con uno de estos dos): un modal dedicado en vez del aviso plano del plugin
// (que igual se sigue viendo dentro del formulario, es el respaldo sin JS).
$applied_now = ! empty( $_GET['mjb_applied'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$apply_error = isset( $_GET['mjb_error'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['mjb_error'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>
<article class="match-jb-detail">
	<header class="match-jb-detail__head">
		<div class="match-jb-detail__who">
			<div class="match-jb-detail__logo"<?php echo ! empty( $job['logo_bg'] ) ? ' style="--logo-bg:' . esc_attr( $job['logo_bg'] ) . '"' : ''; ?>>
				<?php if ( ! empty( $job['logo'] ) ) : ?>
					<img src="<?php echo esc_url( $job['logo'] ); ?>" alt="" decoding="async">
				<?php else : ?>
					<span class="match-logo__monogram" aria-hidden="true"><?php echo esc_html( mb_substr( $job['company'] ?: '—', 0, 1 ) ); ?></span>
				<?php endif; ?>
			</div>
			<div class="match-jb-detail__intro">
				<div>
					<h1 class="match-jb-detail__title"><?php echo esc_html( $job['title'] ); ?></h1>
					<p class="match-jb-detail__company"><?php echo esc_html( $job['company'] ); ?></p>
				</div>
				<ul class="match-jb-tags">
					<?php foreach ( $job['chips'] as $chip ) : ?>
						<li class="match-jb-tag"><?php echo esc_html( $chip ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<div class="match-jb-detail__actions">
			<button class="match-jb-iconbtn match-jb-card__fav<?php echo ! empty( $job['saved'] ) ? ' is-saved' : ''; ?>" type="button" aria-pressed="<?php echo ! empty( $job['saved'] ) ? 'true' : 'false'; ?>" data-job="<?php echo esc_attr( $job['id'] ); ?>">
				<span class="screen-reader-text"><?php esc_html_e( 'Guardar vacante', 'match' ); ?></span>
				<?php echo match_icon( 'bookmark' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<?php if ( $my_stage ) : ?>
				<span class="match-jb-pill match-jb-pill--<?php echo esc_attr( $stages[ $my_stage ]['variant'] ); ?>"><?php echo esc_html( $stages[ $my_stage ]['label'] ); ?></span>
				<button class="match-jb__btn match-jb__btn--dark" type="button" data-status-trigger aria-haspopup="dialog"><?php esc_html_e( 'Ver proceso', 'match' ); ?></button>
			<?php elseif ( $job['closed'] ) : ?>
				<span class="match-jb-pill match-jb-pill--muted"><?php esc_html_e( 'Proceso cerrado', 'match' ); ?></span>
			<?php else : ?>
				<a class="match-jb__btn match-jb__btn--dark match-jb-detail__apply" href="#postular" data-apply-trigger><?php esc_html_e( 'Postular ahora', 'match' ); ?></a>
			<?php endif; ?>
		</div>
	</header>

	<div class="match-jb-detail__body">
		<div class="match-jb-detail__main">
			<section class="match-jb-panel">
				<h2 class="match-jb-panel__title"><?php esc_html_e( 'Descripción del puesto', 'match' ); ?></h2>
				<div class="match-jb-prose"><?php echo wp_kses_post( $job['description_html'] ); ?></div>
			</section>

			<?php if ( $job['benefits_html'] ) : ?>
				<section class="match-jb-panel match-jb-panel--grow">
					<h2 class="match-jb-panel__title"><?php esc_html_e( 'Lo que ofrecemos', 'match' ); ?></h2>
					<div class="match-jb-prose"><?php echo wp_kses_post( $job['benefits_html'] ); ?></div>
				</section>
			<?php endif; ?>
		</div>

		<aside class="match-jb-detail__aside">
			<section class="match-jb-panel">
				<h2 class="match-jb-panel__title"><?php esc_html_e( 'Resumen del rol', 'match' ); ?></h2>
				<hr class="match-jb-panel__rule">
				<dl class="match-jb-summary">
					<?php foreach ( $job['summary'] as $label => $value ) : ?>
						<div class="match-jb-summary__row">
							<dt><?php echo esc_html( $label ); ?></dt>
							<dd><?php echo esc_html( $value ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			</section>

			<?php if ( ! empty( $job['skills'] ) ) : ?>
				<section class="match-jb-panel">
					<h2 class="match-jb-panel__title"><?php esc_html_e( 'Skills requeridas', 'match' ); ?></h2>
					<ul class="match-jb-tags">
						<?php foreach ( $job['skills'] as $skill ) : ?>
							<li class="match-jb-tag"><?php echo esc_html( $skill ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if ( ! empty( $job['requirements'] ) ) : ?>
				<section class="match-jb-panel">
					<h2 class="match-jb-panel__title"><?php esc_html_e( 'Requisitos', 'match' ); ?></h2>
					<ul class="match-jb-prose match-jb-prose--list">
						<?php foreach ( $job['requirements'] as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>
		</aside>
	</div>

	<?php if ( $show_cta ) : ?>
		<section class="match-jb-detail__apply-block" id="postular">
			<?php if ( $can_apply ) : ?>
				<?php echo do_shortcode( '[match_apply_form job="' . (int) $job['id'] . '"]' ); ?>
			<?php else : ?>
				<div class="match-jb-panel">
					<h2 class="match-jb-panel__title"><?php esc_html_e( 'Postular', 'match' ); ?></h2>
					<p class="match-jb-prose"><?php esc_html_e( 'Esta es una vacante de ejemplo: la postulación se habilita cuando el CRM sincronice vacantes reales.', 'match' ); ?></p>
					<?php if ( ! is_user_logged_in() ) : ?>
						<a class="match-jb__btn match-jb__btn--dark" href="<?php echo esc_url( wp_login_url( match_jobboard_url() ) ); ?>"><?php esc_html_e( 'Iniciar sesión para postular', 'match' ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>
</article>

<?php if ( $show_cta ) : ?>
	<!--
	El modal es una mejora progresiva sobre #postular: con JavaScript, "Postular
	ahora" (data-apply-trigger) traslada esa misma sección dentro de este
	<dialog> en vez de duplicar el formulario del plugin (evita IDs repetidos y
	un segundo envío). Sin JavaScript el enlace sigue funcionando: #postular
	nunca se mueve y el ancla baja hasta ahí en la página normal.
	-->
	<dialog class="match-jb-apply-modal" id="match-apply-modal" aria-label="<?php esc_attr_e( 'Postular a la vacante', 'match' ); ?>">
		<div class="match-jb-apply-modal__panel">
			<button class="match-jb-apply-modal__close" type="button" data-apply-close>
				<span class="screen-reader-text"><?php esc_html_e( 'Cerrar', 'match' ); ?></span>
				<?php echo match_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<div class="match-jb-apply-modal__body" data-apply-body></div>
		</div>
	</dialog>
<?php endif; ?>

<?php if ( $my_stage ) : ?>
	<!--
	Contenido y estructura tomados de Figma 4158:4284 ("Detalle del proceso"):
	solo se listan las etapas ya alcanzadas (no las futuras en gris) porque el
	plugin hoy solo trae la fecha de envío, no una fecha por etapa — el resto
	del recorrido se explica con el texto de "Próximo paso" en vez de fechas
	inventadas.
	-->
	<dialog class="match-jb-status-drawer" id="match-status-drawer" aria-label="<?php esc_attr_e( 'Detalle del proceso', 'match' ); ?>">
		<div class="match-jb-status-drawer__panel">
			<button class="match-jb-status-drawer__close" type="button" data-status-close>
				<span class="screen-reader-text"><?php esc_html_e( 'Cerrar', 'match' ); ?></span>
				<?php echo match_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<div class="match-jb-status-drawer__body">
				<p class="match-jb-status-drawer__eyebrow"><?php esc_html_e( 'Detalle del proceso', 'match' ); ?></p>

				<div class="match-jb-status-drawer__intro">
					<h2 class="match-jb-status-drawer__title"><?php echo esc_html( $job['company'] ); ?></h2>
					<p class="match-jb-status-drawer__role"><?php echo esc_html( $job['title'] ); ?></p>
				</div>

				<div class="match-jb-status-drawer__meta">
					<span class="match-jb-pill match-jb-pill--<?php echo esc_attr( $stages[ $my_stage ]['variant'] ); ?>"><?php echo esc_html( $stages[ $my_stage ]['label'] ); ?></span>
					<span class="match-jb-status-drawer__applied">
						<?php echo match_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php echo esc_html( sprintf( /* translators: %s: fecha de postulación. */ __( 'Postulado %s', 'match' ), $application['applied'] ) ); ?>
					</span>
				</div>

				<div class="match-jb-status-drawer__section">
					<p class="match-jb-status-drawer__label"><?php esc_html_e( 'Etapas del proceso', 'match' ); ?></p>
					<ol class="match-jb-status__steps">
						<?php foreach ( array_slice( $stage_order, 0, $current_at + 1 ) as $i => $stage_key ) :
							$is_current = $i === $current_at;
							?>
							<li class="match-jb-status__step<?php echo $is_current ? ' is-current' : ' is-done'; ?>">
								<span class="match-jb-status__rail" aria-hidden="true">
									<span class="match-jb-status__dot"></span>
									<?php if ( ! $is_current ) : ?><span class="match-jb-status__line"></span><?php endif; ?>
								</span>
								<span class="match-jb-status__label">
									<?php echo esc_html( $stages[ $stage_key ]['label'] ); ?>
									<?php if ( $is_current ) : ?>
										<span class="screen-reader-text"><?php esc_html_e( '(estado actual)', 'match' ); ?></span>
									<?php elseif ( 0 === $i ) : ?>
										<span class="match-jb-status__date"><?php echo esc_html( $application['applied'] ); ?></span>
									<?php endif; ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ol>
				</div>

				<div class="match-jb-status-drawer__section">
					<p class="match-jb-status-drawer__label"><?php esc_html_e( 'Próximo paso', 'match' ); ?></p>
					<div class="match-jb-status-drawer__hint">
						<p><?php echo esc_html( $stages[ $my_stage ]['hint'] ); ?></p>
					</div>
				</div>

				<?php if ( $cv ) : ?>
					<div class="match-jb-status-drawer__section">
						<p class="match-jb-status-drawer__label"><?php esc_html_e( 'CV enviado', 'match' ); ?></p>
						<?php get_template_part( 'template-parts/jobboard/cv-row', null, array( 'cv' => $cv ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</dialog>
<?php endif; ?>

<?php if ( $applied_now ) : ?>
	<!-- Figma 4391:3 ("Postulación confirmada"): modal de éxito al volver del formulario. -->
	<dialog class="match-jb-result-modal" id="match-apply-success" aria-label="<?php esc_attr_e( 'Postulación enviada', 'match' ); ?>">
		<div class="match-jb-result-modal__panel">
			<div class="match-jb-result-modal__body">
				<div class="match-jb-result-modal__icon match-jb-result-modal__icon--ok">
					<?php echo match_icon( 'check-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<h2 class="match-jb-result-modal__title"><?php esc_html_e( 'Postulación enviada', 'match' ); ?></h2>
				<p class="match-jb-result-modal__who"><?php echo esc_html( $job['company'] ); ?></p>
				<p class="match-jb-result-modal__role"><?php echo esc_html( $job['title'] ); ?></p>
				<p class="match-jb-result-modal__date"><?php echo esc_html( sprintf( /* translators: %s: fecha de postulación. */ __( 'Postulaste el %s', 'match' ), date_i18n( 'j M, Y', current_time( 'timestamp' ) ) ) ); ?></p>
				<hr class="match-jb-result-modal__rule">
				<p class="match-jb-result-modal__hint"><?php esc_html_e( 'Puedes hacer seguimiento de tu proceso en Mis procesos.', 'match' ); ?></p>
			</div>
			<div class="match-jb-result-modal__footer">
				<button class="match-jb__btn match-jb__btn--light" type="button" data-modal-close><?php esc_html_e( 'Cerrar', 'match' ); ?></button>
				<a class="match-jb__btn match-jb__btn--dark" href="<?php echo esc_url( match_jobboard_page_url( 'template-procesos.php' ) ); ?>"><?php esc_html_e( 'Ver Mis Procesos', 'match' ); ?></a>
			</div>
		</div>
	</dialog>
<?php endif; ?>

<?php if ( $apply_error ) : ?>
	<!-- Mismo shell que el modal de éxito (Figma solo diseñó "Postulación confirmada"): no hay nodo de error en el archivo, así que se adaptó el mismo patrón visual en rojo. -->
	<dialog class="match-jb-result-modal" id="match-apply-error" aria-label="<?php esc_attr_e( 'No se pudo enviar tu postulación', 'match' ); ?>">
		<div class="match-jb-result-modal__panel">
			<div class="match-jb-result-modal__body">
				<div class="match-jb-result-modal__icon match-jb-result-modal__icon--error">
					<?php echo match_icon( 'error-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<h2 class="match-jb-result-modal__title"><?php esc_html_e( 'No se pudo enviar tu postulación', 'match' ); ?></h2>
				<p class="match-jb-result-modal__who"><?php echo esc_html( $job['company'] ); ?></p>
				<p class="match-jb-result-modal__role"><?php echo esc_html( $job['title'] ); ?></p>
				<hr class="match-jb-result-modal__rule">
				<p class="match-jb-result-modal__hint"><?php echo esc_html( $apply_error ); ?></p>
			</div>
			<div class="match-jb-result-modal__footer">
				<button class="match-jb__btn match-jb__btn--light" type="button" data-modal-close><?php esc_html_e( 'Cerrar', 'match' ); ?></button>
				<button class="match-jb__btn match-jb__btn--dark" type="button" data-apply-trigger><?php esc_html_e( 'Reintentar', 'match' ); ?></button>
			</div>
		</div>
	</dialog>
<?php endif; ?>
