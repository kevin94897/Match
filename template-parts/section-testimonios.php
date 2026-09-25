<?php
/**
 * Empresas que confiaron en Match (Figma: node 3502:3374).
 * Textos y fotos: match_home() (inc/home-data.php).
 */
defined( 'ABSPATH' ) || exit;

$home   = match_home();
$review = $home['t_review'];
$logos  = array_intersect_key( match_client_logos(), array_flip( $home['t_logos'] ) );
?>
<section class="match-testimonios">
	<?php match_section_head( $home['t_eyebrow'], $home['t_title'], $home['t_title_2'] ); ?>

	<div class="match-testimonios__grid">
		<div class="match-testimonios__photo">
			<img src="<?php echo esc_url( $home['t_photo'] ); ?>" alt="" loading="lazy" decoding="async">
			<span class="match-testimonios__plus" aria-hidden="true"><?php echo match_icon( 'plus-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<div class="match-testimonios__cta">
				<p>
					<strong><?php echo esc_html( $home['t_cta_strong'] ); ?></strong>
					<?php echo esc_html( $home['t_cta_text'] ); ?>
				</p>
				<a class="match-btn match-btn--primary" href="#contacto">
					<?php echo esc_html( $home['t_cta_label'] ); ?>
					<span class="match-btn__icon"><?php echo match_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</a>
			</div>
		</div>

		<div class="match-testimonios__body" data-aos="fade-up" data-aos-delay="100">
			<p class="match-testimonios__intro">
				<?php echo esc_html( $home['t_intro'] ); ?>
				<span class="is-secondary"><?php echo esc_html( $home['t_intro_2'] ); ?></span>
			</p>

			<div class="match-testimonios__cards">
				<blockquote class="match-testimonial">
					<div class="match-testimonial__top">
						<div class="match-testimonial__avatar">
							<img src="<?php echo esc_url( $review['avatar'] ); ?>" alt="" loading="lazy" decoding="async">
						</div>
						<div>
							<p class="match-testimonial__quote"><?php echo esc_html( $review['quote'] ); ?></p>
							<p class="match-testimonial__text"><?php echo esc_html( $review['text'] ); ?></p>
						</div>
					</div>
					<footer class="match-testimonial__author">
						<p class="match-testimonial__name"><?php echo esc_html( $review['name'] ); ?></p>
						<p class="match-testimonial__role">
							<span><?php echo esc_html( $review['role'] ); ?></span>
							<span><?php echo esc_html( $review['company'] ); ?></span>
						</p>
					</footer>
				</blockquote>

				<div class="match-testimonios__side">
					<div class="match-card match-testimonios__note">
						<p class="match-testimonios__note-title"><?php echo esc_html( $home['t_note_title'] ); ?></p>
						<p class="match-testimonios__note-text"><?php echo esc_html( $home['t_note_text'] ); ?></p>
					</div>
					<div class="match-card match-logos" role="group" aria-label="<?php esc_attr_e( 'Empresas donde trabajan nuestros profesionales', 'match' ); ?>">
						<div class="match-logos__track">
							<?php // Dos pasadas para que el desplazamiento sea continuo (ver .match-marquee en hero.php). ?>
							<?php for ( $pass = 0; $pass < 2; $pass++ ) : ?>
								<?php foreach ( $logos as $slug => $name ) : ?>
									<img src="<?php echo esc_url( get_theme_file_uri( "assets/img/logos/{$slug}.png" ) ); ?>" alt="<?php echo esc_attr( $pass ? '' : $name ); ?>" loading="lazy" decoding="async"<?php echo $pass ? ' aria-hidden="true"' : ''; ?>>
								<?php endforeach; ?>
							<?php endfor; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
