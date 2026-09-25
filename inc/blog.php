<?php
/**
 * Blog (Figma: Prensa & News 3455:142, Blog — Entrada 3933:77).
 *
 * El listado vive en la página con la plantilla "Blog" (template-blog.php).
 * Filtra por ?categoria= y ?buscar= y pagina con ?pagina=, así que los
 * archivos de categoría redirigen allí para que exista una sola URL por vista.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Entradas por página: la grilla repite un patrón de 7 (destacada, 5
 * tarjetas y otra destacada).
 */
const MATCH_BLOG_PER_PAGE = 7;

/**
 * ID de la página que usa la plantilla Blog; 0 si no existe.
 */
function match_blog_page_id(): int {
	static $id = null;

	if ( null === $id ) {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => 'template-blog.php', // phpcs:ignore WordPress.DB.SlowDBQuery
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		$id    = (int) ( $pages[0] ?? 0 );
	}

	return $id;
}

/**
 * URL del listado, opcionalmente filtrado por categoría.
 */
function match_blog_url( string $category = '' ): string {
	$id  = match_blog_page_id();
	$url = $id ? get_permalink( $id ) : home_url( '/' );
	return $category ? add_query_arg( 'categoria', $category, $url ) : $url;
}

/**
 * Categorías que aparecen como filtros (las que tienen entradas, sin la
 * categoría por defecto).
 *
 * @return WP_Term[]
 */
function match_blog_categories(): array {
	return get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'exclude'    => array( (int) get_option( 'default_category' ) ),
			'orderby'    => 'term_id',
		)
	);
}

/**
 * Categoría que se muestra en tarjetas y cabecera: la primera que no sea la
 * de por defecto.
 */
function match_post_category( int $post_id ): ?WP_Term {
	foreach ( get_the_category( $post_id ) as $term ) {
		if ( (int) get_option( 'default_category' ) !== $term->term_id ) {
			return $term;
		}
	}
	return null;
}

/**
 * Fecha corta en español ("20 mar, 2026"), independiente del idioma del
 * sitio.
 */
function match_short_date( int $post_id ): string {
	$months = array( 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic' );
	$time   = (int) get_post_time( 'U', false, $post_id );
	return sprintf( '%d %s, %d', (int) wp_date( 'j', $time ), $months[ (int) wp_date( 'n', $time ) - 1 ], (int) wp_date( 'Y', $time ) );
}

/**
 * Minutos de lectura estimados (200 palabras por minuto).
 */
function match_reading_time( int $post_id ): int {
	$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Entradas relacionadas: primero de la misma categoría, luego las más
 * recientes.
 *
 * @return int[]
 */
function match_related_posts( int $post_id, int $count = 3 ): array {
	$term = match_post_category( $post_id );
	$ids  = array();

	if ( $term ) {
		$ids = get_posts(
			array(
				'cat'            => $term->term_id,
				'post__not_in'   => array( $post_id ),
				'posts_per_page' => $count,
				'fields'         => 'ids',
			)
		);
	}

	if ( count( $ids ) < $count ) {
		$ids = array_merge(
			$ids,
			get_posts(
				array(
					'post__not_in'   => array_merge( array( $post_id ), $ids ),
					'posts_per_page' => $count - count( $ids ),
					'fields'         => 'ids',
				)
			)
		);
	}

	return array_map( 'intval', $ids );
}

/**
 * Los archivos de categoría usan el listado del blog con su filtro.
 */
function match_blog_redirect_categories(): void {
	if ( is_category() && match_blog_page_id() ) {
		wp_safe_redirect( match_blog_url( (string) get_queried_object()->slug ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'match_blog_redirect_categories' );

/**
 * Newsletter: valida y avisa por correo al equipo, luego vuelve al listado
 * con el estado en la URL.
 */
function match_handle_newsletter(): void {
	$back = match_blog_url();

	if ( ! isset( $_POST['match_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['match_newsletter_nonce'] ), 'match_newsletter' ) || ! empty( $_POST['match_web'] ) ) {
		wp_safe_redirect( add_query_arg( 'newsletter', 'error', $back ) . '#newsletter' );
		exit;
	}

	$nombre = sanitize_text_field( wp_unslash( $_POST['nombre'] ?? '' ) );
	$correo = sanitize_email( wp_unslash( $_POST['correo'] ?? '' ) );

	if ( ! is_email( $correo ) ) {
		wp_safe_redirect( add_query_arg( 'newsletter', 'error', $back ) . '#newsletter' );
		exit;
	}

	$to   = get_theme_mod( 'match_email', get_option( 'admin_email' ) );
	$sent = wp_mail( $to, '[Match] Nueva suscripción al newsletter', "Nombre: {$nombre}\nCorreo: {$correo}", array( 'Reply-To: ' . $correo ) );

	wp_safe_redirect( add_query_arg( 'newsletter', $sent ? 'ok' : 'error', $back ) . '#newsletter' );
	exit;
}
add_action( 'admin_post_match_newsletter', 'match_handle_newsletter' );
add_action( 'admin_post_nopriv_match_newsletter', 'match_handle_newsletter' );
