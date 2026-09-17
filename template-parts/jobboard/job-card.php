<?php
/**
 * Tarjeta de vacante del panel (Figma: Card — Vacante (Row), node 4529:51).
 *
 * @var array $args { job: array (ver match_job_data) }
 */
defined( 'ABSPATH' ) || exit;

$job       = $args['job'] ?? array();
$status    = $args['status'] ?? ''; // p. ej. "Postulado"
$date_text = $args['date_text'] ?? ''; // p. ej. "Guardado el 3 abr, 2026"
if ( ! $job ) {
	return;
}
?>
<article class="match-jb-card">
	<div class="match-jb-card__top">
		<div class="match-jb-card__who">
			<div class="match-jb-card__logo"<?php echo ! empty( $job['logo_bg'] ) ? ' style="--logo-bg:' . esc_attr( $job['logo_bg'] ) . '"' : ''; ?>>
				<?php if ( $job['logo'] ) : ?>
					<img src="<?php echo esc_url( $job['logo'] ); ?>" alt="" loading="lazy" decoding="async">
				<?php else : ?>
					<span class="match-logo__monogram" aria-hidden="true"><?php echo esc_html( mb_substr( $job['company'] ?: '—', 0, 1 ) ); ?></span>
				<?php endif; ?>
			</div>
			<div class="match-jb-card__names">
				<p class="match-jb-card__company"><?php echo esc_html( $job['company'] ); ?></p>
				<h3 class="match-jb-card__role"><a href="<?php echo esc_url( $job['url'] ); ?>"><?php echo esc_html( $job['title'] ); ?></a></h3>
			</div>
		</div>
		<div class="match-jb-card__aside">
		<?php if ( $status ) : ?>
			<span class="match-jb-pill"><?php echo esc_html( $status ); ?></span>
		<?php endif; ?>
		<button class="match-jb-card__fav<?php echo $job['saved'] ? ' is-saved' : ''; ?>" type="button" aria-pressed="<?php echo $job['saved'] ? 'true' : 'false'; ?>" data-job="<?php echo esc_attr( $job['id'] ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Guardar vacante', 'match' ); ?></span>
			<?php echo match_icon( 'bookmark' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		</div>
	</div>

	<div class="match-jb-card__meta">
		<p class="match-jb-card__salary"><?php echo esc_html( $job['salary'] ?: __( 'Salario confidencial', 'match' ) ); ?></p>
		<ul class="match-jb-card__features">
			<?php if ( $job['place'] ) : ?>
				<li><?php echo match_icon( 'meta-location' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $job['place'] ); ?></li>
			<?php endif; ?>
			<?php if ( $job['type'] ) : ?>
				<li><?php echo match_icon( 'meta-clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $job['type'] ); ?></li>
			<?php endif; ?>
		</ul>
	</div>

	<div class="match-jb-card__bottom">
		<?php if ( $date_text ) : ?>
			<p class="match-jb-card__date"><?php echo esc_html( $date_text ); ?></p>
		<?php elseif ( $job['date'] ) : ?>
			<time class="match-jb-card__date" datetime="<?php echo esc_attr( $job['datetime'] ); ?>">
				<?php
				/* translators: %s: fecha */
				printf( esc_html__( 'Publicado %s', 'match' ), esc_html( $job['date'] ) );
				?>
			</time>
		<?php endif; ?>
		<a class="match-jb__btn match-jb__btn--dark match-jb__btn--sm" href="<?php echo esc_url( $job['url'] ); ?>"><?php esc_html_e( 'Ver vacante', 'match' ); ?></a>
	</div>
</article>
