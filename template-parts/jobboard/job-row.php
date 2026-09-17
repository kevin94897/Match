<?php
/**
 * Fila de vacante del listado (Figma: Card — Vacante (Row) de Entrance,
 * node 4016:3390).
 *
 * @var array $args { job: array (ver match_job_data) }
 */
defined( 'ABSPATH' ) || exit;

$job = $args['job'] ?? array();
if ( ! $job ) {
	return;
}
?>
<article class="match-jb-row">
	<div class="match-jb-row__top">
		<div class="match-jb-app__who">
			<div class="match-jb-app__logo"<?php echo ! empty( $job['logo_bg'] ) ? ' style="--logo-bg:' . esc_attr( $job['logo_bg'] ) . '"' : ''; ?>>
				<?php if ( ! empty( $job['logo'] ) ) : ?>
					<img src="<?php echo esc_url( $job['logo'] ); ?>" alt="" loading="lazy" decoding="async">
				<?php else : ?>
					<span class="match-logo__monogram" aria-hidden="true"><?php echo esc_html( mb_substr( $job['company'] ?: '—', 0, 1 ) ); ?></span>
				<?php endif; ?>
			</div>
			<div class="match-jb-app__names">
				<p class="match-jb-app__company"><?php echo esc_html( $job['company'] ); ?></p>
				<h2 class="match-jb-app__role"><a href="<?php echo esc_url( $job['url'] ); ?>"><?php echo esc_html( $job['title'] ); ?></a></h2>
			</div>
		</div>

		<div class="match-jb-row__aside">
			<div class="match-jb-row__right">
				<button class="match-jb-card__fav<?php echo ! empty( $job['saved'] ) ? ' is-saved' : ''; ?>" type="button" aria-pressed="<?php echo ! empty( $job['saved'] ) ? 'true' : 'false'; ?>" data-job="<?php echo esc_attr( $job['id'] ); ?>">
					<span class="screen-reader-text"><?php esc_html_e( 'Guardar vacante', 'match' ); ?></span>
					<?php echo match_icon( 'bookmark' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>
				<p class="match-jb-row__salary"><?php echo esc_html( $job['salary'] ?: __( 'Salario confidencial', 'match' ) ); ?></p>
			</div>
			<ul class="match-jb-app__features">
				<?php if ( ! empty( $job['type'] ) ) : ?>
					<li><?php echo match_icon( 'meta-location' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $job['type'] ); ?></li>
				<?php endif; ?>
				<?php if ( ! empty( $job['place'] ) ) : ?>
					<li><?php echo match_icon( 'meta-clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $job['place'] ); ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div class="match-jb-row__bottom">
		<ul class="match-jb-tags match-jb-tags--white">
			<?php foreach ( array_slice( (array) ( $job['skills'] ?? array() ), 0, 4 ) as $skill ) : ?>
				<li class="match-jb-tag"><?php echo esc_html( $skill ); ?></li>
			<?php endforeach; ?>
		</ul>
		<div class="match-jb-row__actions">
			<?php if ( ! empty( $job['date'] ) ) : ?>
				<time class="match-jb-card__date" datetime="<?php echo esc_attr( $job['datetime'] ); ?>">
					<?php
					/* translators: %s: fecha */
					printf( esc_html__( 'Publicado %s', 'match' ), esc_html( $job['date'] ) );
					?>
				</time>
			<?php endif; ?>
			<a class="match-jb__btn match-jb__btn--dark match-jb__btn--sm" href="<?php echo esc_url( $job['url'] ); ?>"><?php esc_html_e( 'Ver vacante', 'match' ); ?></a>
		</div>
	</div>
</article>
