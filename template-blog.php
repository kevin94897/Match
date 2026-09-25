<?php
/**
 * Template Name: Blog
 *
 * Listado de entradas (Figma: Prensa & News, node 3455:142). Filtros por
 * ?categoria= y ?buscar=, paginación con ?pagina= (ver inc/blog.php).
 */
defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtros públicos de solo lectura.
$category = isset( $_GET['categoria'] ) ? sanitize_title( wp_unslash( $_GET['categoria'] ) ) : '';
$search   = isset( $_GET['buscar'] ) ? sanitize_text_field( wp_unslash( $_GET['buscar'] ) ) : '';
$paged    = isset( $_GET['pagina'] ) ? max( 1, absint( $_GET['pagina'] ) ) : 1;
// phpcs:enable

$query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => MATCH_BLOG_PER_PAGE,
		'paged'               => $paged,
		'category_name'       => $category,
		's'                   => $search,
		'ignore_sticky_posts' => true,
	)
);

get_header();
?>
<section class="match-blog-hero">
	<div class="match-blog-hero__head" data-aos="fade-up">
		<h1 class="match-blog-hero__title"><?php esc_html_e( 'Blog.', 'match' ); ?></h1>
		<div class="match-blog-hero__lead">
			<p><?php esc_html_e( 'Todo lo que logramos, se documenta.', 'match' ); ?></p>
			<p class="is-muted"><?php esc_html_e( 'Aquí encontrarás noticias, comunicados y novedades sobre el trabajo de Match.', 'match' ); ?></p>
		</div>
	</div>

	<div class="match-blog-filters" data-aos="fade-up" data-aos-delay="150">
		<form class="match-blog-search" role="search" method="get" action="<?php echo esc_url( match_blog_url() ); ?>">
			<label class="screen-reader-text" for="match-blog-q"><?php esc_html_e( 'Buscar en el blog', 'match' ); ?></label>
			<?php echo match_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<input id="match-blog-q" type="search" name="buscar" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Buscar artículos, noticias...', 'match' ); ?>">
			<?php if ( $category ) : ?>
				<input type="hidden" name="categoria" value="<?php echo esc_attr( $category ); ?>">
			<?php endif; ?>
		</form>

		<nav class="match-blog-pills" aria-label="<?php esc_attr_e( 'Categorías', 'match' ); ?>">
			<a class="match-blog-pill<?php echo $category ? '' : ' is-active'; ?>" href="<?php echo esc_url( match_blog_url() ); ?>"<?php echo $category ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'Ver todas las noticias', 'match' ); ?></a>
			<?php foreach ( match_blog_categories() as $term ) : ?>
				<?php $active = $term->slug === $category; ?>
				<a class="match-blog-pill<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( match_blog_url( $term->slug ) ); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $term->name ); ?></a>
			<?php endforeach; ?>
		</nav>
	</div>
</section>

<section class="match-blog-list" aria-label="<?php esc_attr_e( 'Artículos', 'match' ); ?>">
	<?php if ( $query->have_posts() ) : ?>
		<div class="match-blog-grid">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				// Primera y última del patrón de 7 van destacadas (ocupan dos columnas).
				$slot = $query->current_post % MATCH_BLOG_PER_PAGE;
				get_template_part(
					'template-parts/blog/card',
					null,
					array(
						'post_id'  => get_the_ID(),
						'featured' => 0 === $slot || 6 === $slot,
						'delay'    => ( $slot % 3 ) * 100,
					)
				);
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<?php if ( $query->max_num_pages > 1 ) : ?>
			<nav class="match-blog-pager" aria-label="<?php esc_attr_e( 'Paginación', 'match' ); ?>">
				<?php
				echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput
					array(
						'base'      => add_query_arg( 'pagina', '%#%' ),
						'format'    => '',
						'current'   => $paged,
						'total'     => $query->max_num_pages,
						'prev_text' => __( 'Anterior', 'match' ),
						'next_text' => __( 'Siguiente', 'match' ),
						'mid_size'  => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<div class="match-blog-empty">
			<p class="match-blog-empty__title"><?php esc_html_e( 'No encontramos artículos', 'match' ); ?></p>
			<p><?php esc_html_e( 'Prueba con otros términos o revisa todas las noticias.', 'match' ); ?></p>
			<a class="match-btn match-btn--secondary" href="<?php echo esc_url( match_blog_url() ); ?>"><?php esc_html_e( 'Ver todas las noticias', 'match' ); ?></a>
		</div>
	<?php endif; ?>
</section>

<?php
get_template_part( 'template-parts/blog/newsletter' );

get_footer();
