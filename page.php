<?php
/**
 * Página estática.
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
			</header>

			<div class="match-page__content match-job__prose">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<nav class="match-page__pages">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
