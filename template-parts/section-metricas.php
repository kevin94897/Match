<?php
/**
 * Resultados medibles (Figma: node 3502:3469).
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="match-metricas" id="resultados">
	<?php
	match_section_head(
		__( 'Resultados medibles', 'match' ),
		__( 'La velocidad y precisión,', 'match' ),
		__( 'medidas en números reales', 'match' )
	);
	?>

	<div class="match-metricas__grid">
		<div class="match-stat-hero">
			<div class="match-stat-hero__bg" aria-hidden="true"></div>
			<figure class="match-stat-hero__figure" aria-hidden="true">
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/metricas-ejecutivo.webp' ) ); ?>" alt="" loading="lazy" decoding="async">
			</figure>
			<div class="match-stat-hero__top">
				<p class="match-stat-hero__title"><?php esc_html_e( 'Velocidad de contratación', 'match' ); ?></p>
				<p class="match-stat-hero__sub"><?php esc_html_e( 'Nuestro récord end-to-end.', 'match' ); ?></p>
			</div>
			<div class="match-stat-hero__bottom">
				<p class="match-stat-hero__value"><?php esc_html_e( '1 DÍA', 'match' ); ?></p>
				<p class="match-stat-hero__text"><?php esc_html_e( 'Récord de contratación, desde la vacante hasta la oferta aceptada.', 'match' ); ?></p>
			</div>
		</div>

		<div class="match-metricas__col">
			<div class="match-metricas__row">
				<div class="match-stat match-stat--split">
					<div class="match-stat__group">
						<p class="match-stat__label"><?php esc_html_e( 'Presentación de candidatos:', 'match' ); ?></p>
						<p class="match-stat__value"><?php esc_html_e( '1 hora récord', 'match' ); ?></p>
					</div>
					<div class="match-stat__group">
						<p class="match-stat__label"><?php esc_html_e( 'Garantías usadas por clientes:', 'match' ); ?></p>
						<p class="match-stat__value">00</p>
					</div>
				</div>

				<div class="match-stat match-stat--center">
					<div class="match-gauge">
						<?php echo file_get_contents( get_theme_file_path( 'assets/img/gauge.svg' ) ); // phpcs:ignore ?>
						<p class="match-gauge__value">100%</p>
					</div>
					<p class="match-stat__title"><?php esc_html_e( 'Primera terna', 'match' ); ?></p>
					<p class="match-stat__text"><?php esc_html_e( 'De nuestros candidatos contratados están en nuestra primera terna.', 'match' ); ?></p>
				</div>
			</div>

			<div class="match-metricas__row">
				<div class="match-stat">
					<p class="match-stat__big">91%</p>
					<p class="match-stat__title"><?php esc_html_e( 'HiPo o Top Performer', 'match' ); ?></p>
					<p class="match-stat__text"><?php esc_html_e( 'De nuestros candidatos son considerados de alto potencial o desempeño sobresaliente.', 'match' ); ?></p>
				</div>
				<div class="match-stat">
					<p class="match-stat__big">00</p>
					<p class="match-stat__title"><?php esc_html_e( 'Rotación', 'match' ); ?></p>
					<p class="match-stat__text"><?php esc_html_e( 'Nuestros candidatos tienen una permanencia promedio de tres años, ahorrando costos de selección.', 'match' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>
