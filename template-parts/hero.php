<?php
/**
 * Hero de la portada (Figma: Hero, node 3502:3319).
 */
defined( 'ABSPATH' ) || exit;

$open_jobs = match_open_jobs_count();
$logos     = match_client_logos();
?>
<section class="match-hero">
	<div class="match-hero__media">
		<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/hero.webp' ) ); ?>" alt="" width="1920" height="1080" fetchpriority="high" decoding="async">
	</div>

	<div class="match-hero__inner">
		<h1 class="match-hero__wordmark"><?php echo match_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>

		<p class="match-hero__lead" data-aos="fade-up">
			<?php esc_html_e( 'Sé parte de la base de datos ejecutiva', 'match' ); ?>
			<strong><?php esc_html_e( 'más importante de la región', 'match' ); ?></strong>.
			<?php esc_html_e( 'Las empresas líderes nos confían sus búsquedas.', 'match' ); ?>
		</p>

		<div class="match-glass match-hero__card" data-aos="fade-up" data-aos-delay="150">
			<div class="match-hero__card-text">
				<p class="match-hero__card-title"><?php esc_html_e( 'Da el primer paso', 'match' ); ?></p>
				<p class="match-hero__card-sub"><?php esc_html_e( 'Accede a las posiciones más exclusivas del mercado ejecutivo', 'match' ); ?></p>
			</div>
			<a class="match-btn match-btn--primary match-btn--block match-btn--glow" href="<?php echo esc_url( match_jobs_url() ); ?>">
				<?php esc_html_e( 'Encuentra tu próximo puesto', 'match' ); ?>
			</a>
		</div>

		<div class="match-hero__bottom" data-aos="fade-up" data-aos-delay="300">
			<p class="match-hero__proof">
				<?php
				printf(
					/* translators: %s: número de vacantes */
					esc_html( _n( '+%s posición de alta dirección abierta ahora', '+%s posiciones de alta dirección abiertas ahora', $open_jobs, 'match' ) ),
					esc_html( number_format_i18n( $open_jobs ) )
				);
				?>
			</p>

			<div class="match-marquee" aria-label="<?php esc_attr_e( 'Empresas que confían en Match', 'match' ); ?>">
				<div class="match-marquee__track">
					<?php for ( $pass = 0; $pass < 2; $pass++ ) : ?>
						<?php foreach ( $logos as $slug => $name ) : ?>
							<img src="<?php echo esc_url( get_theme_file_uri( "assets/img/logos/{$slug}.png" ) ); ?>" alt="<?php echo esc_attr( $pass ? '' : $name ); ?>" loading="lazy" decoding="async"<?php echo $pass ? ' aria-hidden="true"' : ''; ?>>
						<?php endforeach; ?>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</div>
</section>
