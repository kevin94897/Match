<?php
/**
 * Entrada individual.
 */
defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'match-page' ); ?>>
		<div class="match-container">
			<header class="match-page__head">
				<h1 class="match-h2"><?php the_title(); ?></h1>
				<p class="match-entry__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( get_the_date() ); ?>
					</time>
				</p>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="match-page__media"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>

			<div class="match-page__content match-job__prose">
				<?php the_content(); ?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
