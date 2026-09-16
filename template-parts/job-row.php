<?php
/**
 * Fila de vacante del Job Board en la portada (Figma: Card — Vacante (Row)).
 *
 * @var array $args { job: array (ver match_job_data), open: bool }
 */
defined( 'ABSPATH' ) || exit;

$job  = $args['job'] ?? array();
$open = ! empty( $args['open'] );

if ( ! $job ) {
	return;
}

$row_id = 'job-' . sanitize_html_class( $job['id'] );
?>
<article class="match-job<?php echo $open ? ' is-open' : ''; ?>" id="<?php echo esc_attr( $row_id ); ?>">
	<div class="match-job__main">
		<div class="match-job__head">
			<div class="match-job__top">
				<div class="match-job__identity">
					<div class="match-job__logo">
						<?php if ( $job['logo'] ) : ?>
							<img src="<?php echo esc_url( $job['logo'] ); ?>" alt="" loading="lazy" decoding="async">
						<?php else : ?>
							<span class="match-logo__monogram" aria-hidden="true"><?php echo esc_html( mb_substr( $job['company'] ?: '—', 0, 1 ) ); ?></span>
						<?php endif; ?>
					</div>
					<div>
						<p class="match-job__company"><?php echo esc_html( $job['company'] ); ?></p>
						<h3 class="match-job__role"><a href="<?php echo esc_url( $job['url'] ); ?>"><?php echo esc_html( $job['title'] ); ?></a></h3>
					</div>
				</div>

				<div class="match-job__aside">
					<button class="match-job__fav<?php echo $job['saved'] ? ' is-saved' : ''; ?>" type="button" aria-pressed="<?php echo $job['saved'] ? 'true' : 'false'; ?>" data-job="<?php echo esc_attr( $job['id'] ); ?>">
						<span class="screen-reader-text"><?php esc_html_e( 'Guardar vacante', 'match' ); ?></span>
						<?php echo match_icon( 'fav' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
					<?php if ( $job['salary'] ) : ?>
						<p class="match-job__salary"><?php echo esc_html( $job['salary'] ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<ul class="match-job__meta">
				<?php if ( $job['type'] ) : ?>
					<li><?php echo match_icon( 'meta-location' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $job['type'] ); ?></li>
				<?php endif; ?>
				<?php if ( $job['place'] ) : ?>
					<li><?php echo match_icon( 'meta-clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $job['place'] ); ?></li>
				<?php endif; ?>
			</ul>
		</div>

		<div class="match-job__foot">
			<ul class="match-job__tags">
				<?php foreach ( array_slice( $job['skills'], 0, 4 ) as $skill ) : ?>
					<li class="match-pill"><?php echo esc_html( $skill ); ?></li>
				<?php endforeach; ?>
			</ul>
			<div class="match-job__actions">
				<?php if ( $job['date'] ) : ?>
					<time class="match-job__date" datetime="<?php echo esc_attr( $job['datetime'] ); ?>">
						<?php
						/* translators: %s: fecha */
						printf( esc_html__( 'Publicado %s', 'match' ), esc_html( $job['date'] ) );
						?>
					</time>
				<?php endif; ?>
				<a class="match-btn match-btn--secondary" href="<?php echo esc_url( $job['url'] ); ?>">
					<?php esc_html_e( 'Postular ahora', 'match' ); ?>
					<span class="match-btn__icon"><?php echo match_icon( 'arrow-dark' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</a>
			</div>
		</div>
	</div>

	<button class="match-btn match-btn--round match-job__toggle<?php echo $open ? ' is-active' : ''; ?>" type="button" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $row_id ); ?>">
		<span class="screen-reader-text"><?php esc_html_e( 'Ver detalle', 'match' ); ?></span>
		<span class="match-job__icon-open"><?php echo match_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		<span class="match-job__icon-close"><?php echo match_icon( 'minus' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	</button>
</article>
