<?php
/**
 * Template Name: Solución
 *
 * Interna de solución (Figma: Executive 3232:4955, Outplacement 3280:450).
 * El contenido sale de inc/soluciones-data.php según el slug de la página;
 * la clase match-sol--{tema} en <body> activa la paleta de cada solución
 * (ver "Temas" en assets/css/solucion.css).
 */
defined( 'ABSPATH' ) || exit;

$solucion = match_solucion_data( get_post_field( 'post_name' ) ) ?? match_solucion_data( 'executive' );

add_filter(
	'body_class',
	static function ( array $classes ) use ( $solucion ): array {
		$classes[] = 'match-sol--' . sanitize_html_class( $solucion['theme'] );
		return $classes;
	}
);

get_header();

get_template_part( 'template-parts/solucion/hero', null, $solucion );
get_template_part( 'template-parts/solucion/metricas', null, $solucion );
get_template_part( 'template-parts/solucion/diferenciador', null, $solucion );
get_template_part( 'template-parts/solucion/perfiles', null, $solucion );
get_template_part( 'template-parts/solucion/clientes', null, $solucion );
get_template_part( 'template-parts/solucion/testimonios', null, $solucion );
get_template_part( 'template-parts/section', 'contacto', array( 'lead' => $solucion['contact_lead'], 'lead_muted' => '' ) );
get_template_part( 'template-parts/solucion/toast', null, $solucion );

get_footer();
