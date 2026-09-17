<?php
/**
 * Template Name: Job Board — Mis procesos
 *
 * Postulaciones del candidato (Figma: Job-Board/Mis procesos 4105:97;
 * vacío 4406:3). Exige sesión. Datos de match_user_applications(): las
 * postulaciones del plugin o, en modo demo, cinco filas de ejemplo.
 */
defined( 'ABSPATH' ) || exit;

$order = isset( $_GET['orden'] ) && 'antiguo' === $_GET['orden'] ? 'antiguo' : 'reciente'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$rows  = match_user_applications( $order );
$sorts = array(
	'reciente' => __( 'Más reciente', 'match' ),
	'antiguo'  => __( 'Más antiguo', 'match' ),
);

get_header( 'jobboard' );
?>
<div class="match-jb">
	<?php get_template_part( 'template-parts/jobboard/sidebar', null, array( 'current' => 'procesos' ) ); ?>

	<main id="main" class="match-jb__content">
		<?php get_template_part( 'template-parts/jobboard/topbar' ); ?>

		<div class="match-jb__body match-jb__body--list">
			<header class="match-jb-list__head" data-aos="fade-up">
				<h1 class="match-jb-list__title"><?php esc_html_e( 'Postulaciones', 'match' ); ?></h1>
				<form class="match-jb-sort" method="get">
					<label class="match-jb-sort__label">
						<span><?php esc_html_e( 'Ordenar por:', 'match' ); ?></span>
						<strong data-sort-label><?php echo esc_html( $sorts[ $order ] ); ?></strong>
						<?php echo match_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<select name="orden" onchange="this.form.submit()" aria-label="<?php esc_attr_e( 'Ordenar por', 'match' ); ?>">
							<?php foreach ( $sorts as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $order, $value ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<noscript><button class="match-jb__btn match-jb__btn--light match-jb__btn--sm" type="submit"><?php esc_html_e( 'Aplicar', 'match' ); ?></button></noscript>
				</form>
			</header>

			<hr class="match-jb__rule">

			<?php if ( $rows ) : ?>
				<div class="match-jb-list" data-aos="fade-up" data-aos-delay="80">
					<?php foreach ( $rows as $row ) : ?>
						<?php get_template_part( 'template-parts/jobboard/application-row', null, $row ); ?>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="match-jb-empty match-jb-empty--page" data-aos="fade-up" data-aos-delay="80">
					<span class="match-jb-empty__icon" aria-hidden="true"><?php echo match_icon( 'briefcase' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<p class="match-jb-empty__title"><?php esc_html_e( 'Aún no tienes postulaciones', 'match' ); ?></p>
					<p class="match-jb-empty__text"><?php esc_html_e( 'Cuando apliques a una vacante, podrás hacer seguimiento de tu proceso desde aquí.', 'match' ); ?></p>
					<a class="match-jb__btn match-jb__btn--dark" href="<?php echo esc_url( match_jobs_url() ); ?>"><?php esc_html_e( 'Postular ahora', 'match' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</main>
</div>
<?php
get_footer( 'jobboard' );
