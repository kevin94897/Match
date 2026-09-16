<?php
/**
 * Plantilla de respaldo.
 *
 * WordPress la exige en la raíz del tema y la usa cuando ninguna plantilla más
 * específica coincide.
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="match-container match-archive">
	<?php if ( have_posts() ) : ?>

		<header class="match-archive__head">
			<?php if ( is_home() && ! is_front_page() ) : ?>
				<h1 class="match-h2"><?php single_post_title(); ?></h1>
			<?php elseif ( is_search() ) : ?>
				<h1 class="match-h2">
					<?php
					printf(
						/* translators: %s: términos buscados */
						esc_html__( 'Resultados para %s', 'match' ),
						'<span class="is-secondary">' . esc_html( get_search_query() ) . '</span>'
					);
					?>
				</h1>
			<?php else : ?>
				<?php the_archive_title( '<h1 class="match-h2">', '</h1>' ); ?>
				<?php the_archive_description( '<div class="match-archive__intro">', '</div>' ); ?>
			<?php endif; ?>
		</header>

		<div class="match-archive__list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'match-entry' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="match-entry__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
							<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
						</a>
					<?php endif; ?>

					<div class="match-entry__body">
						<h2 class="match-entry__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>

						<p class="match-entry__meta">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( get_the_date() ); ?>
							</time>
						</p>

						<div class="match-entry__excerpt"><?php the_excerpt(); ?></div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<nav class="mjb-pager" aria-label="<?php esc_attr_e( 'Paginación', 'match' ); ?>">
			<?php
			the_posts_pagination(
				array(
					'prev_text' => __( 'Anterior', 'match' ),
					'next_text' => __( 'Siguiente', 'match' ),
					'mid_size'  => 1,
				)
			);
			?>
		</nav>

	<?php else : ?>

		<div class="mjb-empty">
			<p class="mjb-empty__title"><?php esc_html_e( 'No encontramos nada', 'match' ); ?></p>
			<p class="mjb-empty__text"><?php esc_html_e( 'Prueba con otros términos o vuelve al inicio.', 'match' ); ?></p>
			<a class="match-btn match-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Ir al inicio', 'match' ); ?>
			</a>
		</div>

	<?php endif; ?>
</div>
<?php
get_footer();
