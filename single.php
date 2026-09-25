<?php
/**
 * Entrada del blog (Figma: Blog — Entrada, node 3933:77).
 *
 * Foto destacada con la tarjeta del título montada encima, contenido del
 * editor y tres artículos relacionados (ver inc/blog.php).
 */
defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$term    = match_post_category( get_the_ID() );
	$related = match_related_posts( get_the_ID() );
	?>
	<article <?php post_class( 'match-post' ); ?>>
		<div class="match-post__hero">
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="match-post__cover" data-aos="fade-up">
					<?php the_post_thumbnail( 'full', array( 'alt' => '', 'fetchpriority' => 'high' ) ); ?>
				</figure>
			<?php endif; ?>

			<header class="match-post__head<?php echo has_post_thumbnail() ? '' : ' match-post__head--flat'; ?>" data-aos="fade-up" data-aos-delay="100">
				<?php if ( $term ) : ?>
					<a class="match-post__tag" href="<?php echo esc_url( match_blog_url( $term->slug ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
				<?php endif; ?>

				<h1 class="match-post__title"><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?>
					<p class="match-post__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<p class="match-post__meta">
					<span><?php echo match_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( match_short_date( get_the_ID() ) ); ?></time></span>
					<span class="match-post__dot" aria-hidden="true"></span>
					<span>
						<?php echo match_icon( 'clock-countdown' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php
						/* translators: %d: minutos de lectura */
						echo esc_html( sprintf( __( '%d min.', 'match' ), match_reading_time( get_the_ID() ) ) );
						?>
						<span class="screen-reader-text"><?php esc_html_e( 'de lectura', 'match' ); ?></span>
					</span>
				</p>
			</header>

			<div class="match-post__content">
				<?php the_content(); ?>
			</div>
		</div>
	</article>

	<?php if ( $related ) : ?>
		<section class="match-post-related">
			<header class="match-post-related__head" data-aos="fade-up">
				<p class="match-post-related__brand"><?php esc_html_e( 'blog®', 'match' ); ?></p>
				<h2 class="match-post-related__title"><?php esc_html_e( 'Podría interesarte', 'match' ); ?></h2>
			</header>
			<div class="match-post-related__grid">
				<?php
				foreach ( $related as $i => $related_id ) {
					get_template_part( 'template-parts/blog/card', null, array( 'post_id' => $related_id, 'delay' => $i * 100 ) );
				}
				?>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
