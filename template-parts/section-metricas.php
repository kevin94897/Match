<?php
/**
 * Resultados medibles (Figma: node 3502:3469).
 * Textos y foto: match_home() (inc/home-data.php).
 */
defined( 'ABSPATH' ) || exit;

$home  = match_home();
$hero  = $home['m_hero'];
$gauge = $home['m_gauge'];
?>
<section class="match-metricas" id="resultados">
	<?php match_section_head( $home['m_eyebrow'], $home['m_title'], $home['m_title_2'] ); ?>

	<div class="match-metricas__grid" data-aos="fade-up" data-aos-delay="100">
		<div class="match-stat-hero">
			<div class="match-stat-hero__bg" aria-hidden="true"></div>
			<figure class="match-stat-hero__figure" aria-hidden="true">
				<img src="<?php echo esc_url( $hero['photo'] ); ?>" alt="" loading="lazy" decoding="async">
			</figure>
			<div class="match-stat-hero__top">
				<p class="match-stat-hero__title"><?php echo esc_html( $hero['title'] ); ?></p>
				<p class="match-stat-hero__sub"><?php echo esc_html( $hero['sub'] ); ?></p>
			</div>
			<div class="match-stat-hero__bottom">
				<p class="match-stat-hero__value"><?php echo esc_html( $hero['value'] ); ?></p>
				<p class="match-stat-hero__text"><?php echo esc_html( $hero['text'] ); ?></p>
			</div>
		</div>

		<div class="match-metricas__col">
			<div class="match-metricas__row">
				<div class="match-stat match-stat--split">
					<?php foreach ( $home['m_split'] as $stat ) : ?>
						<div class="match-stat__group">
							<p class="match-stat__label"><?php echo esc_html( $stat['label'] ); ?></p>
							<p class="match-stat__value"><?php echo esc_html( $stat['value'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="match-stat match-stat--center">
					<div class="match-gauge">
						<?php echo file_get_contents( get_theme_file_path( 'assets/img/gauge.svg' ) ); // phpcs:ignore ?>
						<p class="match-gauge__value"><?php echo esc_html( $gauge['value'] ); ?></p>
					</div>
					<p class="match-stat__title"><?php echo esc_html( $gauge['title'] ); ?></p>
					<p class="match-stat__text"><?php echo esc_html( $gauge['text'] ); ?></p>
				</div>
			</div>

			<div class="match-metricas__row">
				<?php foreach ( $home['m_stats'] as $stat ) : ?>
					<div class="match-stat">
						<p class="match-stat__big"><?php echo esc_html( $stat['value'] ); ?></p>
						<p class="match-stat__title"><?php echo esc_html( $stat['title'] ); ?></p>
						<p class="match-stat__text"><?php echo esc_html( $stat['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
