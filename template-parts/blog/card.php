<?php
/**
 * Tarjeta de artículo (Figma: Card 3955:9269 y Card — Noticia 3920:5539).
 *
 * La destacada pone la foto de fondo con el texto encima y el extracto; la
 * normal apila foto y cuerpo blanco.
 *
 * @var array $args { post_id, featured, delay }
 */
defined( 'ABSPATH' ) || exit;

$post_id  = (int) $args['post_id'];
$featured = ! empty( $args['featured'] );
$term     = match_post_category( $post_id );
$date     = match_short_date( $post_id );
?>
<article class="match-post-card<?php echo $featured ? ' match-post-card--featured' : ''; ?>" data-aos="fade-up" data-aos-delay="<?php echo (int) ( $args['delay'] ?? 0 ); ?>">
	<div class="match-post-card__media">
		<?php if ( has_post_thumbnail( $post_id ) ) : ?>
			<?php echo get_the_post_thumbnail( $post_id, $featured ? 'full' : 'large', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) ); ?>
		<?php endif; ?>
	</div>

	<div class="match-post-card__body">
		<?php if ( $featured && $term ) : ?>
			<span class="match-post-card__tag"><?php echo esc_html( $term->name ); ?></span>
		<?php endif; ?>

		<div class="match-post-card__text">
			<p class="match-post-card__meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( $date ); ?></time>
				<?php if ( ! $featured && $term ) : ?>
					<span class="match-post-card__cat">/ <?php echo esc_html( $term->name ); ?></span>
				<?php endif; ?>
			</p>
			<h2 class="match-post-card__title">
				<a class="match-post-card__link" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a>
			</h2>
			<?php if ( $featured && has_excerpt( $post_id ) ) : ?>
				<p class="match-post-card__excerpt"><?php echo esc_html( get_the_excerpt( $post_id ) ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</article>
